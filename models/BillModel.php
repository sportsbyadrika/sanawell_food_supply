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
        (route_id, customer_id, bill_from, bill_to, bill_type, total_amount, tax_amount, final_amount)
        VALUES (?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $data['route_id'],
        $data['customer_id'],
        $data['bill_from'],
        $data['bill_to'],
        $data['bill_type'],
        $data['total_amount'],
        $data['tax_amount'],
        $data['final_amount']
    ]);

    return $this->db->lastInsertId();
}

public function getBills()
{
    return $this->db->query("
        SELECT b.*, c.name, c.mobile 
        FROM bills b
        JOIN customers c ON c.id = b.customer_id
        ORDER BY b.id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
}

public function getPendingBills($search, $route_id)
{
    $stmt = $this->db->prepare("
        SELECT b.*, c.name, c.mobile
        FROM bills b
        JOIN customers c ON c.id = b.customer_id
        WHERE b.status = 'generated'
        AND (c.name LIKE ? OR c.mobile LIKE ?)
        AND b.route_id = ?
    ");

    $stmt->execute(["%$search%", "%$search%", $route_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function saveReceipt($data)
{
    $stmt = $this->db->prepare("
        INSERT INTO receipts
        (bill_id, customer_id, route_id, receipt_date, amount, payment_mode, transaction_ref, transaction_date)
        VALUES (?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $data['bill_id'],
        $data['customer_id'],
        $data['route_id'],
        $data['receipt_date'],
        $data['amount'],
        $data['payment_mode'],
        $data['transaction_ref'],
        $data['transaction_date']
    ]);
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
        (SELECT IFNULL(SUM(amount),0) FROM receipts) as total_collection
        FROM bills
    ")->fetch(PDO::FETCH_ASSOC);
}

}