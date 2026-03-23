<?php

class BillModel extends BaseModel
{

public function getCustomerWiseData($route_id, $from, $to)
{
    $stmt = $this->db->prepare("
        SELECT d.delivery_order_id, o.customer_id, SUM(d.amount) as total
        FROM daily_delivery_bill d
        JOIN delivery_orders o ON o.id = d.delivery_order_id
        WHERE o.route_id = ?
        AND DATE(d.created_at) BETWEEN ? AND ?
        GROUP BY o.customer_id
    ");
    $stmt->execute([$route_id, $from, $to]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getCustomerItems($customer_id, $from, $to)
{
    $stmt = $this->db->prepare("
        SELECT d.product_id, SUM(d.qty) as qty, SUM(d.amount) as amount
        FROM daily_delivery_bill d
        JOIN delivery_orders o ON o.id = d.delivery_order_id
        WHERE o.customer_id = ?
        AND DATE(d.created_at) BETWEEN ? AND ?
        GROUP BY d.product_id
    ");
    $stmt->execute([$customer_id, $from, $to]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getRoutes()
{
    $stmt = $this->db->query("SELECT id, name FROM routes ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function createBill($data)
{
    $stmt = $this->db->prepare("
        INSERT INTO bills 
        (route_id, customer_id, bill_from, bill_to, bill_type, bill_date, total_amount, tax_amount, final_amount, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['route_id'],
        $data['customer_id'],
        $data['bill_from'],
        $data['bill_to'],
        $data['bill_type'],
        date('Y-m-d'),
        $data['total_amount'],
        $data['tax_amount'],
        $data['final_amount'],
        'BILL GENERATED'
    ]);

     
    return $this->db->lastInsertId();
   
}

public function getBills()
{
    return $this->db->query("
        SELECT b.*, c.name, c.mobile , c.address
        FROM bills b
        JOIN customers c ON c.id = b.customer_id
        ORDER BY b.id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
}

public function getBillsByRoute($route_id) {

    $stmt = $this->db->prepare("
        SELECT b.*, c.name
        FROM bills b
        JOIN customers c ON c.id = b.customer_id
        WHERE c.route_id = ?
    ");

    $stmt->execute([$route_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getBillsSummary()
{
    
    $query = "
        SELECT 
            COALESCE((SELECT SUM(final_amount) FROM bills), 0) AS total_demand,
            COALESCE((SELECT SUM(amount) FROM receipts), 0) AS total_collection
    ";

    $stmt = $this->db->prepare($query);
    $stmt->execute();

    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $row['balance'] = $row['total_demand'] - $row['total_collection'];

    return $row;
}
public function getAllBills()
{
    $sql = "SELECT 
                b.id,
                b.customer_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount AS total,

                (b.final_amount - IFNULL(SUM(r.amount), 0)) AS balance,

                c.name AS customer_name,
                c.mobile

            FROM bills b

            JOIN customers c ON c.id = b.customer_id

            LEFT JOIN receipts r ON r.bill_id = b.id

            GROUP BY b.id

            ORDER BY b.id DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getPendingBills()
{
    $sql = "SELECT 
                b.id,
                b.customer_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount AS total,

                (b.final_amount - IFNULL(SUM(r.amount), 0)) AS balance,

                c.name AS customer_name,
                c.mobile

            FROM bills b

            JOIN customers c ON c.id = b.customer_id

            LEFT JOIN receipts r ON r.bill_id = b.id

            GROUP BY b.id

            HAVING balance > 0

            ORDER BY b.id DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getBillById($id)
{
    $sql = "SELECT 
                b.id,
                b.customer_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount,

                (b.final_amount - IFNULL(SUM(r.amount), 0)) AS balance,

                c.name AS customer_name,
                c.mobile,
                c.address

            FROM bills b

            JOIN customers c ON c.id = b.customer_id

            LEFT JOIN receipts r ON r.bill_id = b.id

            WHERE b.id = ?

            GROUP BY b.id";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function getReceipts($search, $route_id)
{
    $stmt = $this->db->prepare("
        SELECT r.*, c.name
        FROM receipts r
        JOIN customers c ON c.id = r.customer_id
        WHERE (c.name LIKE ? OR c.mobile LIKE ?)
        AND c.route_id = ?
        ORDER BY r.id DESC
    ");

    $stmt->execute(["%$search%", "%$search%", $route_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function saveReceipt($data)
{
    $stmt = $this->db->prepare("
        INSERT INTO receipts 
        (bill_id, receipt_date, amount, payment_mode, transaction_ref, transaction_date, status, verified_date, verified_user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    return $stmt->execute([
        $data['bill_id'],
        $data['receipt_date'],
        $data['amount'],
        $data['mode'],
        $data['transaction_ref'],
        $data['transaction_date'],
        $data['status'],
        $data['verified_date'],
        $data['verified_user_id']
    ]);
}

public function getTotalCollection($bill_id)
{
    $stmt = $this->db->prepare("
        SELECT IFNULL(SUM(amount),0) as total 
        FROM receipts 
        WHERE bill_id = ?
    ");

    $stmt->execute([$bill_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

public function verifyReceipt($receipt_id, $user_id)
{
    $stmt = $this->db->prepare("
        UPDATE receipts
        SET status='verified', verified_date=NOW(), verified_user_id=?
        WHERE id=?
    ");

    $stmt->execute([$user_id, $receipt_id]);
}

public function insertBillItem($data)
{
    $stmt = $this->db->prepare("
        INSERT INTO bill_items (bill_id, product_id, qty, amount)
        VALUES (:bill_id, :product_id, :qty, :amount)
    ");

    return $stmt->execute([
        ':bill_id' => $data['bill_id'],
        ':product_id' => $data['product_id'],
        ':qty' => $data['qty'],
        ':amount' => $data['amount']
    ]);
}

public function getDashboardSummary()
{
    return $this->db->query("
        SELECT 
        IFNULL(SUM(final_amount),0) as total_demand,
        (SELECT IFNULL(SUM(amount),0) FROM receipts WHERE status='verified') as total_collection,
        (
            IFNULL(SUM(final_amount),0) - 
            (SELECT IFNULL(SUM(amount),0) FROM receipts WHERE status='verified')
        ) as balance
        FROM bills
    ")->fetch(PDO::FETCH_ASSOC);
}

public function getNotificationCounts()
{
    return $this->db->query("
        SELECT
        (SELECT COUNT(*) FROM bills WHERE status='BILL GENERATED') as pending_bills,
        (SELECT COUNT(*) FROM receipts WHERE status='verified') as verified_receipts
    ")->fetch(PDO::FETCH_ASSOC);
}
public function updateBillStatus($bill_id)
{
    // 1. Get bill total
    $stmt = $this->db->prepare("SELECT final_amount FROM bills WHERE id=?");
    $stmt->execute([$bill_id]);
    $bill = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Get total paid
    $stmt = $this->db->prepare("
        SELECT IFNULL(SUM(amount),0) as paid 
        FROM receipts 
        WHERE bill_id=? AND status IN ('entry','verified')
    ");
    $stmt->execute([$bill_id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Compare
    if ($payment['paid'] >= $bill['final_amount']) {
        $stmt = $this->db->prepare("
            UPDATE bills 
            SET status='CLOSED' 
            WHERE id=?
        ");
        $stmt->execute([$bill_id]);
    }
}

public function getReceiptSummary($search = '', $route_id = '')
{
    
    $demand = $this->db->query("
        SELECT SUM(final_amount) as total_demand 
        FROM bills
    ")->fetch(PDO::FETCH_ASSOC)['total_demand'] ?? 0;

    
    $collection = $this->db->query("
        SELECT SUM(amount) as total_collection 
        FROM receipts
    ")->fetch(PDO::FETCH_ASSOC)['total_collection'] ?? 0;

    
    $balance = $demand - $collection;

    return [
        'total_demand' => $demand,
        'total_collection' => $collection,
        'balance' => $balance
    ];
}

}