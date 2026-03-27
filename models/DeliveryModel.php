<?php

class DeliveryModel
{
      private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

   
   public function getOrdersByRoute($route_id)
{
    $today = date('Y-m-d');

    $sql = "SELECT d.*, c.name, c.mobile
            FROM delivery_orders d
            JOIN customers c ON c.id = d.customer_id
            WHERE d.route_id = ?
            AND d.delivery_date = ?
            ORDER BY d.order_no ASC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$route_id, $today]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getOrder($id) {

        $sql = "SELECT d.*, c.name, c.mobile
                FROM delivery_orders d
                JOIN customers c ON c.id = d.customer_id
                WHERE d.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   

    public function updateDelivery($id, $status, $reason) {

        $sql = "UPDATE delivery_orders 
                SET status = ?, reason = ?, delivered_at = NOW()
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $reason, $id]);
    }

 
public function getTodayDeliveries($routeId)
{
    $stmt = $this->db->prepare("
SELECT 
    do.id,
    do.order_no,
    c.name,
    c.mobile,

   GROUP_CONCAT(
    CONCAT(
        p.name,
        '|',
        p.variant,
        '|',
        doi.quantity,
        ',',
        doi.added_qty,
        ',',
        (doi.quantity + doi.added_qty - doi.cancelled_qty)
    )
    SEPARATOR '||'
) AS products,
    do.status

FROM delivery_orders do
JOIN customers c ON do.customer_id = c.id
JOIN delivery_order_items doi ON doi.delivery_order_id = do.id
JOIN products p ON doi.product_id = p.id

WHERE do.route_id = ?
AND DATE(do.delivery_date) = CURDATE()

GROUP BY do.id
ORDER BY do.order_no ASC
");

    $stmt->execute([$routeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deliveryExistsToday($routeId)
{
    $today = date('Y-m-d');

    $stmt = $this->db->prepare("
        SELECT COUNT(*) as count
        FROM delivery_orders
        WHERE route_id = ?
        AND delivery_date = ?
    ");

    $stmt->execute([$routeId, $today]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['count'] > 0;
}
public function getDeliveryCounts($routeId)
{
    $stmt = $this->db->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
            SUM(CASE WHEN status = 'not_delivered' THEN 1 ELSE 0 END) as failed
        FROM delivery_orders
        WHERE route_id = ?
        AND delivery_date = CURDATE()
    ");

    $stmt->execute([$routeId]);
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);
    $delivered = (int) ($counts['delivered'] ?? 0);
    $failed = (int) ($counts['failed'] ?? 0);
    $total = (int) ($counts['total'] ?? 0);
    $counts['pending'] = max(0, $total - ($delivered + $failed));

    return $counts;
}

public function deliveryExists($routeId)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) as total
        FROM delivery_orders
        WHERE route_id = ?
        AND delivery_date = CURDATE()
    ");

    $stmt->execute([$routeId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'] > 0;
}

public function generateDeliveryForRoute($routeId, $driverId, $vehicleId, $tripDate, $tripStartTime)
{
    $db = $this->db;

    // 1. Delete existing deliveries for this route & date
    $delete = $db->prepare("
        DELETE FROM delivery_orders
        WHERE route_id = ? AND delivery_date = ?
    ");
    $delete->execute([$routeId, $tripDate]);

    // 2. ALWAYS refresh route_customers (FIXED ISSUE)
    $deleteRC = $db->prepare("DELETE FROM route_customers WHERE route_id = ?");
    $deleteRC->execute([$routeId]);

    $sync = $db->prepare("
        INSERT INTO route_customers (route_id, customer_id, delivery_order, created_at)
        SELECT
            c.route_id,
            c.id,
            ROW_NUMBER() OVER (ORDER BY c.name ASC),
            NOW()
        FROM customers c
        WHERE c.route_id = ?
        AND c.status = 1
    ");
    $sync->execute([$routeId]);

    // 3. Fetch customers for route
    $stmt = $db->prepare("
        SELECT rc.customer_id, c.name
        FROM route_customers rc
        JOIN customers c ON c.id = rc.customer_id
        WHERE rc.route_id = ?
        AND c.status = 1
        ORDER BY rc.delivery_order ASC
    ");
    $stmt->execute([$routeId]);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($customers)) {
        return;
    }

    $orderNo = 1;

    foreach ($customers as $customer) {

        // 4. Insert delivery order
        $insertOrder = $db->prepare("
            INSERT INTO delivery_orders
            (route_id, customer_id, driver_id, vehicle_id, order_no, delivery_date, trip_start_time, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");

        $insertOrder->execute([
            $routeId,
            $customer['customer_id'],
            $driverId,
            $vehicleId,
            $orderNo,
            $tripDate,
            $tripStartTime
        ]);

        $deliveryOrderId = $db->lastInsertId();

        // 5. Get customer products
        $productStmt = $db->prepare("
            SELECT
                cp.product_id,
                cp.quantity AS normal_qty,
                cp.rate,
                cr.requested_qty
            FROM customer_products cp
            LEFT JOIN change_requests cr
                ON cp.customer_id = cr.customer_id
                AND cp.product_id = cr.product_id
                AND DATE(cr.request_date) = ?
            WHERE cp.customer_id = ?
            AND cp.status = 1
        ");

        $productStmt->execute([
            $tripDate,
            $customer['customer_id']
        ]);

        $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

        // ✅ Skip customers with no products
        if (empty($products)) {
            continue;
        }

        foreach ($products as $product) {

            $normalQty = $product['normal_qty'];
            $extraQty  = $product['requested_qty'] ?? 0;

            $totalQty = $normalQty + $extraQty;
            $total    = $totalQty * $product['rate'];

            $insertItem = $db->prepare("
                INSERT INTO delivery_order_items
                (delivery_order_id, product_id, quantity, added_qty, cancelled_qty, rate, total_amount)
                VALUES (?, ?, ?, ?, 0, ?, ?)
            ");

            $insertItem->execute([
                $deliveryOrderId,
                $product['product_id'],
                $normalQty,
                $extraQty,
                $product['rate'],
                $total
            ]);
        }

        // 6. Extra products (only from change_requests)
        $extraStmt = $db->prepare("
            SELECT cr.product_id, cr.requested_qty
            FROM change_requests cr
            WHERE cr.customer_id = ?
            AND DATE(cr.request_date) = ?
            AND cr.product_id NOT IN (
                SELECT product_id FROM customer_products WHERE customer_id = ?
            )
        ");

        $extraStmt->execute([
            $customer['customer_id'],
            $tripDate,
            $customer['customer_id']
        ]);

        $extraProducts = $extraStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($extraProducts as $extra) {

            $rateStmt = $db->prepare("SELECT rate FROM products WHERE id = ?");
            $rateStmt->execute([$extra['product_id']]);
            $rate = $rateStmt->fetchColumn() ?? 0;

            $total = $extra['requested_qty'] * $rate;

            $insertItem = $db->prepare("
                INSERT INTO delivery_order_items
                (delivery_order_id, product_id, quantity, added_qty, cancelled_qty, rate, total_amount)
                VALUES (?, ?, 0, ?, 0, ?, ?)
            ");

            $insertItem->execute([
                $deliveryOrderId,
                $extra['product_id'],
                $extra['requested_qty'],
                $rate,
                $total
            ]);
        }

        $orderNo++;
    }
}
public function getDeliveriesByRoute($routeId)
{
    $stmt = $this->db->prepare("
        SELECT
            do.id,
            do.order_no,
            c.name,
            c.mobile,
            c.address,
            p.name AS product_name,
            p.variant,
           (doi.quantity + doi.added_qty - doi.cancelled_qty) AS quantity,
            do.status

        FROM delivery_orders do

        JOIN customers c 
            ON do.customer_id = c.id

        JOIN delivery_order_items doi 
            ON doi.delivery_order_id = do.id

        JOIN products p 
            ON doi.product_id = p.id

        WHERE do.route_id = ?
        AND DATE(do.delivery_date) = CURDATE()

        ORDER BY do.order_no ASC
    ");

    $stmt->execute([$routeId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $deliveries = [];

    foreach ($rows as $row) {

        $orderId = $row['id'];

        if (!isset($deliveries[$orderId])) {

            $deliveries[$orderId] = [
                'id' => $row['id'],
                'order_no' => $row['order_no'],
                'name' => $row['name'],
                'mobile' => $row['mobile'],
                'address' => $row['address'],
                'status' => $row['status'],
                'products' => []
            ];
        }

        $deliveries[$orderId]['products'][] = [
            'name' => $row['product_name'],
            'variant' => $row['variant'],
            'qty' => $row['quantity']
        ];
    }

    return array_values($deliveries);
}

public function markAsDelivered($orderId)
{
$stmt = $this->db->prepare("
UPDATE delivery_orders
SET status='delivered', delivered_at=NOW()
WHERE id=?
");

$stmt->execute([$orderId]);
}

public function getRouteIdByOrder($orderId)
{
    $stmt = $this->db->prepare("
        SELECT route_id FROM delivery_orders WHERE id = ?
    ");

    $stmt->execute([$orderId]);
    return $stmt->fetchColumn();
}
public function getRouteWithTodayDelivery()
{
    $sql = "
        SELECT route_id
        FROM delivery_orders
        WHERE delivery_date = CURDATE()
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['route_id'] : null;
}

public function getDeliveryLoadSummary($routeId)
{
    $stmt = $this->db->prepare("
        SELECT
            p.name,
            p.variant,

           SUM(doi.quantity + doi.added_qty) as base_qty,
           SUM(CASE WHEN doi.added_qty > 0 THEN doi.added_qty ELSE 0 END) as added_qty,
SUM(CASE WHEN doi.added_qty < 0 THEN ABS(doi.added_qty) ELSE 0 END) as cancelled_qty,

           SUM(doi.quantity + doi.added_qty) as total_qty
        FROM delivery_orders do

        JOIN delivery_order_items doi
            ON doi.delivery_order_id = do.id

        JOIN products p
            ON p.id = doi.product_id

        WHERE do.route_id = ?
       AND DATE(do.delivery_date) = CURDATE()

        GROUP BY doi.product_id

        ORDER BY total_qty DESC
    ");

    $stmt->execute([$routeId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function markNotDelivered($orderId,$reason)
{
$stmt = $this->db->prepare("
UPDATE delivery_orders
SET status='not_delivered',
failure_reason=?,
delivered_at=NOW()
WHERE id=?
");

$stmt->execute([$reason,$orderId]);
}

public function getRouteProductTotals($routeId)
{
    $sql = "SELECT
        p.name AS product_name,
        p.variant,

        SUM(doi.quantity) AS normal_qty,
        SUM(doi.added_qty) AS added_qty,
        SUM(doi.cancelled_qty) AS cancelled_qty,

        SUM(doi.quantity + doi.added_qty - doi.cancelled_qty) AS total_qty

    FROM delivery_orders d
    JOIN delivery_order_items doi ON d.id = doi.delivery_order_id
    JOIN products p ON p.id = doi.product_id

    WHERE d.route_id = ?
    AND d.delivery_date = CURDATE()

    GROUP BY p.name, p.variant";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$routeId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function increaseQty($id)
{
    $sql = "
        UPDATE delivery_order_items
        SET added_qty = added_qty + 1
        WHERE id = ?
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
}
public function decreaseQty($id)
{
    $sql = "
        UPDATE delivery_order_items
        SET cancelled_qty = cancelled_qty + 1
        WHERE id = ?
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
}
public function cancelItem($itemId)
{
    $sql = "UPDATE delivery_order_items
            SET cancelled_qty = quantity
            WHERE id = ?";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$itemId]);
}

public function getQty($id)
{
    $sql = "
        SELECT quantity + added_qty - cancelled_qty AS qty
        FROM delivery_order_items
        WHERE id = ?
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? (int)$row['qty'] : 0;
}

public function getCustomerProducts($customer_id,$from,$to)
{

$sql="

SELECT 
product_id,
SUM(quantity) qty,
rate,
SUM(total_price) amount

FROM delivery_order_items

JOIN delivery_orders
ON delivery_orders.id = delivery_order_items.delivery_order_id

WHERE delivery_orders.customer_id = ?
AND delivery_orders.delivery_date BETWEEN ? AND ?

GROUP BY product_id

";

return $this->db->query($sql,[$customer_id,$from,$to])->fetchAll();

}

public function updateOrderStatus($order_id, $status)
{
    $stmt = $this->db->prepare("
        UPDATE delivery_orders 
        SET status = ? 
        WHERE id = ?
    ");
    $stmt->execute([$status, $order_id]);
}

public function updateStatus($order_id, $status, $reason = null, $remarks = null)
{
    $stmt = $this->db->prepare("
        UPDATE delivery_orders 
        SET status = ?, reason = ?, remarks = ?, delivered_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$status, $reason, $remarks, $order_id]);
}

public function getOrderItems($order_id)
{
    $stmt = $this->db->prepare("
        SELECT product_id, quantity, rate, total_amount
        FROM delivery_order_items
        WHERE delivery_order_id = ?
    ");
    $stmt->execute([$order_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function insertDailyBill($data)
{
    $stmt = $this->db->prepare("
        INSERT INTO daily_delivery_bill
        (delivery_order_id, product_id, qty, amount, status, reason, remarks, created_at)
        VALUES (?,?,?,?,?,?,?,NOW())
    ");

    $stmt->execute([
        $data['delivery_order_id'],
        $data['product_id'],
        $data['qty'],
        $data['amount'],
        $data['status'],
        $data['reason'] ?? null,
        $data['remarks'] ?? null,
    ]);
}
public function checkBillExists($order_id)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM daily_delivery_bill 
        WHERE delivery_order_id = ?
    ");
    $stmt->execute([$order_id]);

    return $stmt->fetchColumn() > 0;
}
}
