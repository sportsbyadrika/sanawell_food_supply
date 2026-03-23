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
            'BILL GENERATED',
        ]);

        return $this->db->lastInsertId();
    }

    public function getBills()
    {
        $stmt = $this->db->query("
            SELECT b.*, c.name, c.mobile, c.address
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            ORDER BY b.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBillsByRoute($route_id)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, c.name
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            WHERE c.route_id = ?
        ");

        $stmt->execute([$route_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Dashboard summary for receipt entry.
     *
     * SQL used:
     * - Total Demand: SELECT COALESCE(SUM(final_amount), 0) FROM bills
     * - Total Collection: SELECT COALESCE(SUM(amount), 0) FROM receipts
     * - Balance: demand - collection
     */
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
        $row['balance'] = (float) $row['total_demand'] - (float) $row['total_collection'];

        return $row;
    }

    public function getAllBills()
    {
        $sql = "
            SELECT
                b.id,
                b.customer_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount AS total,
                (b.final_amount - COALESCE(SUM(r.amount), 0)) AS balance,
                c.name AS customer_name,
                c.mobile,
                b.status
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN receipts r ON r.bill_id = b.id
            GROUP BY b.id, b.customer_id, b.bill_date, b.bill_from, b.bill_to, b.bill_type, b.final_amount, c.name, c.mobile, b.status
            ORDER BY b.id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get only pending bills for receipt entry, optionally filtered by customer/mobile.
     * Pending means explicit bill status is Pending OR remaining balance is greater than zero.
     */
    public function getPendingBills($search = null, $routeId = null)
    {
        $sql = "
            SELECT
                b.id,
                b.customer_id,
                b.route_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount AS total,
                COALESCE(SUM(r.amount), 0) AS collected,
                (b.final_amount - COALESCE(SUM(r.amount), 0)) AS balance,
                c.name AS customer_name,
                c.mobile,
                b.status
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN receipts r ON r.bill_id = b.id
            WHERE (:search = '' OR c.name LIKE :search_like OR c.mobile LIKE :search_like)
            GROUP BY
                b.id,
                b.customer_id,
                b.route_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount,
                c.name,
                c.mobile,
                b.status
            HAVING b.status = 'Pending' OR balance > 0
            ORDER BY b.id DESC
        ";

        $search = trim((string) $search);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        $stmt->bindValue(':search_like', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch one bill along with customer details and computed balance.
     */
    public function getBillById($id)
    {
        $sql = "
            SELECT
                b.id,
                b.customer_id,
                b.route_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount,
                COALESCE(SUM(r.amount), 0) AS collected,
                (b.final_amount - COALESCE(SUM(r.amount), 0)) AS balance,
                c.name AS customer_name,
                c.mobile,
                c.address,
                b.status
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN receipts r ON r.bill_id = b.id
            WHERE b.id = ?
            GROUP BY
                b.id,
                b.customer_id,
                b.route_id,
                b.bill_date,
                b.bill_from,
                b.bill_to,
                b.bill_type,
                b.final_amount,
                c.name,
                c.mobile,
                c.address,
                b.status
        ";

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

    public function getReceiptsByBill($bill_id)
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                bill_id,
                route_id,
                receipt_date,
                amount,
                payment_mode,
                transaction_ref,
                transaction_date,
                status,
                verified_date,
                verified_user_id
            FROM receipts
            WHERE bill_id = ?
            ORDER BY receipt_date DESC, id DESC
        ");
        $stmt->execute([$bill_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Save receipt entry using PDO prepared statements only.
     */
    public function saveReceipt($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO receipts
            (
                bill_id,
                route_id,
                receipt_date,
                amount,
                payment_mode,
                transaction_ref,
                transaction_date,
                status,
                verified_date,
                verified_user_id
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['bill_id'],
            $data['route_id'],
            $data['receipt_date'],
            $data['amount'],
            $data['payment_mode'],
            $data['transaction_ref'],
            $data['transaction_date'],
            $data['status'],
            $data['verified_date'],
            $data['verified_user_id'],
        ]);

        return (int) $this->db->lastInsertId();
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
            ':amount' => $data['amount'],
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

    /**
     * Update bill status after receipt save.
     * - balance <= 0 : Paid
     * - otherwise    : Pending
     */
    public function updateBillStatus($bill_id)
    {
        $bill = $this->getBillById($bill_id);

        if (!$bill) {
            return false;
        }

        $newStatus = ((float) $bill['balance'] <= 0) ? 'Paid' : 'Pending';

        $stmt = $this->db->prepare("
            UPDATE bills
            SET status = ?
            WHERE id = ?
        ");

        return $stmt->execute([$newStatus, $bill_id]);
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
            'balance' => $balance,
        ];
    }
}
