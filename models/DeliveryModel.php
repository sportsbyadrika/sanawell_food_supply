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

    public function getOrderItems($order_id) {

        $sql = "SELECT p.product_name, oi.quantity
                FROM delivery_order_items oi
                JOIN products p ON p.id = oi.product_id
                WHERE oi.delivery_order_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$order_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        MAX(do.order_no) AS order_no,
        c.name,

        GROUP_CONCAT(
            CONCAT(
                p.name,' ',p.variant,
                '|',
                (doi.quantity + doi.added_qty - doi.cancelled_qty),
                '|',
                doi.id
            )
            SEPARATOR '||'
        ) AS products,

        do.status

    FROM delivery_orders do
    JOIN customers c ON do.customer_id = c.id
    JOIN delivery_order_items doi ON doi.delivery_order_id = do.id
    JOIN products p ON doi.product_id = p.id

    WHERE do.route_id = ?
    AND do.delivery_date = CURDATE()

    GROUP BY c.id
    ORDER BY order_no ASC
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
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered
        FROM delivery_orders
        WHERE route_id = ?
        AND delivery_date = CURDATE()
    ");

    $stmt->execute([$routeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
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

public function generateDeliveryForRoute($routeId)
{
    $db = $this->db;

    // Get ACTIVE customers assigned to this route
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

        // Create delivery order
        $insertOrder = $db->prepare("
            INSERT INTO delivery_orders
            (route_id, customer_id, order_no, delivery_date, status, created_at)
            VALUES (?, ?, ?, CURDATE(), 'pending', NOW())
        ");

        $insertOrder->execute([
            $routeId,
            $customer['customer_id'],
            $orderNo
        ]);

        $deliveryOrderId = $db->lastInsertId();

        // Get products for the customer
        $productStmt = $db->prepare("
            SELECT product_id, quantity, rate
            FROM customer_products
            WHERE route_id = ?
            AND customer_id = ?
            AND status = 1
        ");

        $productStmt->execute([
            $routeId,
            $customer['customer_id']
        ]);

        $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {

            $total = $product['quantity'] * $product['rate'];

            $insertItem = $db->prepare("
                INSERT INTO delivery_order_items
                (delivery_order_id, product_id, quantity, rate, total_amount)
                VALUES (?, ?, ?, ?, ?)
            ");

            $insertItem->execute([
                $deliveryOrderId,
                $product['product_id'],
                $product['quantity'],
                $product['rate'],
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
            doi.quantity,
            do.status
        FROM delivery_orders do
        JOIN customers c ON do.customer_id = c.id
        JOIN delivery_order_items doi ON doi.delivery_order_id = do.id
        JOIN products p ON doi.product_id = p.id
        WHERE do.route_id = ?
        AND do.delivery_date = CURDATE()
        ORDER BY do.order_no ASC
    ");

    $stmt->execute([$routeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function markAsDelivered($orderId)
{
    $stmt = $this->db->prepare("
        UPDATE delivery_orders
        SET status = 'delivered',
            delivered_at = NOW()
        WHERE id = ?
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

public function getDeliverySummary($from, $to)
{
    $stmt = $this->db->prepare("
        SELECT 
            do.delivery_date,
            do.route_id,
            r.name as route_name,
            COUNT(DISTINCT do.customer_id) as total_customers,
            SUM(doi.quantity) as total_quantity
        FROM delivery_orders do
        JOIN delivery_order_items doi 
            ON doi.delivery_order_id = do.id
        JOIN routes r 
            ON r.id = do.route_id
        WHERE do.delivery_date BETWEEN ? AND ?
        GROUP BY do.delivery_date, do.route_id
        ORDER BY do.delivery_date DESC
    ");

    $stmt->execute([$from, $to]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getDeliveryDetailsByDate($date)
{
    $stmt = $this->db->prepare("
        SELECT 
            do.delivery_date,
            c.name as customer_name,
            p.name as product_name,
            p.variant,
            doi.quantity
        FROM delivery_orders do
        JOIN delivery_order_items doi 
            ON doi.delivery_order_id = do.id
        JOIN customers c 
            ON c.id = do.customer_id
        JOIN products p 
            ON p.id = doi.product_id
        WHERE do.delivery_date = ?
        ORDER BY c.name
    ");

    $stmt->execute([$date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getBillsBetweenDates($from, $to)
{
    $sql = "SELECT 
                b.bill_date,
                c.name AS customer_name,
                b.total_amount,
                b.status
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            WHERE b.bill_date BETWEEN ? AND ?
            ORDER BY b.bill_date DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$from, $to]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getDeliveryDetailsByDateAndRoute($date, $route_id)
{
    $stmt = $this->db->prepare("
        SELECT 
            c.name AS customer_name,
            p.name AS product_name,
            p.variant,
            doi.quantity
        FROM delivery_orders do
        JOIN delivery_order_items doi ON doi.delivery_order_id = do.id
        JOIN customers c ON c.id = do.customer_id
        JOIN products p ON p.id = doi.product_id
        WHERE do.delivery_date = ?
        AND do.route_id = ?
        ORDER BY c.name
    ");

    $stmt->execute([$date, $route_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getCustomerTotalsForPeriod($from, $to)
{
    $sql = "
        SELECT 
            d.customer_id,
            SUM(doi.total) as total_amount
        FROM deliveries d
        JOIN delivery_orders do ON do.delivery_id = d.id
        JOIN delivery_order_items doi ON doi.delivery_order_id = do.id
        WHERE d.delivery_date BETWEEN ? AND ?
        GROUP BY d.customer_id
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$from, $to]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function insertBill($customerId, $totalAmount)
{
    $stmt = $this->db->prepare("
        INSERT INTO bills (customer_id, bill_date, total_amount, status, created_at)
        VALUES (?, CURDATE(), ?, 'pending', NOW())
    ");

    $stmt->execute([$customerId, $totalAmount]);

    return $this->db->lastInsertId();
}

public function getCustomerItemsForPeriod($customerId, $from, $to)
{
    $sql = "
        SELECT 
            doi.product_id,
            SUM(doi.quantity) as quantity,
            doi.price,
            SUM(doi.total) as total
        FROM deliveries d
        JOIN delivery_orders do ON do.delivery_id = d.id
        JOIN delivery_order_items doi ON doi.delivery_order_id = do.id
        WHERE d.customer_id = ?
        AND d.delivery_date BETWEEN ? AND ?
        GROUP BY doi.product_id
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$customerId, $from, $to]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function insertBillItem($billId, $productId, $qty, $price, $total)
{
    $stmt = $this->db->prepare("
        INSERT INTO bill_items (bill_id, product_id, quantity, price, total)
        VALUES (?, ?, ?, ?, ?)
    ");

   
    $stmt->execute([$billId, $productId, $qty, $price, $total]);
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

            SUM(doi.quantity) as base_qty,
            SUM(doi.added_qty) as added_qty,
            SUM(doi.cancelled_qty) as cancelled_qty,

            SUM(doi.quantity + doi.added_qty - doi.cancelled_qty) as total_qty

        FROM delivery_orders do

        JOIN delivery_order_items doi
            ON doi.delivery_order_id = do.id

        JOIN products p
            ON p.id = doi.product_id

        WHERE do.route_id = ?
        AND do.delivery_date = CURDATE()

        GROUP BY doi.product_id

        ORDER BY total_qty DESC
    ");

    $stmt->execute([$routeId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateStatus($delivery_id,$status)
{

$stmt = $this->db->prepare("
UPDATE delivery_orders
SET status=?, delivered_at=NOW()
WHERE id=?
");

$stmt->execute([$status,$delivery_id]);

}

public function markNotDelivered($id,$reason,$remarks)
{

$stmt = $this->db->prepare("
UPDATE delivery_orders
SET status='not_delivered',
reason=?,
remarks=?,
delivered_at=NOW()
WHERE id=?
");

$stmt->execute([$reason,$remarks,$id]);

}

public function getRouteProductTotals($routeId)
{
    $sql = "SELECT 
p.name AS product_name,
p.variant,
SUM(doi.quantity + doi.added_qty - doi.cancelled_qty) AS qty
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
}