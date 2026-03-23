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

    public function getRouteById($routeId)
    {
        $stmt = $this->db->prepare("SELECT id, name FROM routes WHERE id = ? LIMIT 1");
        $stmt->execute([(int) $routeId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getRouteName($routeId)
    {
        $route = $this->getRouteById($routeId);

        return $route['name'] ?? 'All Routes';
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

    public function getBills($routeId = null)
    {
        $sql = "
            SELECT b.*, c.name, c.mobile, c.address, r.name AS route_name
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN routes r ON r.id = b.route_id
        ";

        if ($routeId !== null) {
            $sql .= ' WHERE b.route_id = :route_id';
        }

        $sql .= ' ORDER BY b.id DESC';

        $stmt = $this->db->prepare($sql);

        if ($routeId !== null) {
            $stmt->bindValue(':route_id', (int) $routeId, PDO::PARAM_INT);
        }

        $stmt->execute();

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
     */
    public function getBillsSummary($routeId = null)
    {
        $sql = "
            SELECT
                COALESCE(SUM(b.final_amount), 0) AS total_demand,
                COALESCE(SUM(receipt_totals.total_collection), 0) AS total_collection
            FROM bills b
            LEFT JOIN (
                SELECT bill_id, SUM(amount) AS total_collection
                FROM receipts
                GROUP BY bill_id
            ) receipt_totals ON receipt_totals.bill_id = b.id
        ";

        if ($routeId !== null) {
            $sql .= ' WHERE b.route_id = :route_id';
        }

        $stmt = $this->db->prepare($sql);

        if ($routeId !== null) {
            $stmt->bindValue(':route_id', (int) $routeId, PDO::PARAM_INT);
        }

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_demand' => 0, 'total_collection' => 0];
        $row['balance'] = (float) $row['total_demand'] - (float) $row['total_collection'];

        return $row;
    }

    public function getAllBills($routeId = null)
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
                (b.final_amount - COALESCE(SUM(r.amount), 0)) AS balance,
                c.name AS customer_name,
                c.mobile,
                rt.name AS route_name,
                b.status
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN routes rt ON rt.id = b.route_id
            LEFT JOIN receipts r ON r.bill_id = b.id
        ";

        if ($routeId !== null) {
            $sql .= ' WHERE b.route_id = :route_id';
        }

        $sql .= "
            GROUP BY b.id, b.customer_id, b.route_id, b.bill_date, b.bill_from, b.bill_to, b.bill_type, b.final_amount, c.name, c.mobile, rt.name, b.status
            ORDER BY b.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        if ($routeId !== null) {
            $stmt->bindValue(':route_id', (int) $routeId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get only pending bills for receipt entry, optionally filtered by route and customer/mobile search.
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
                rt.name AS route_name,
                b.status
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN routes rt ON rt.id = b.route_id
            LEFT JOIN receipts r ON r.bill_id = b.id
            WHERE (:search = '' OR c.name LIKE :search_like OR c.mobile LIKE :search_like)
              AND (:route_id = 0 OR b.route_id = :route_id)
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
                rt.name,
                b.status
            HAVING b.status = 'Pending' OR balance > 0
            ORDER BY b.id DESC
        ";

        $search = trim((string) $search);
        $routeId = (int) $routeId;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        $stmt->bindValue(':search_like', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':route_id', $routeId, PDO::PARAM_INT);
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
                rt.name AS route_name,
                b.status
            FROM bills b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN routes rt ON rt.id = b.route_id
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
                rt.name,
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

    public function getDashboardSummary($routeId = null)
    {
        $sql = "
            SELECT
                COALESCE(SUM(b.final_amount), 0) AS total_demand,
                COALESCE(SUM(receipt_totals.total_collection), 0) AS total_collection
            FROM bills b
            LEFT JOIN (
                SELECT bill_id, SUM(amount) AS total_collection
                FROM receipts
                GROUP BY bill_id
            ) receipt_totals ON receipt_totals.bill_id = b.id
        ";

        if ($routeId !== null) {
            $sql .= ' WHERE b.route_id = :route_id';
        }

        $stmt = $this->db->prepare($sql);

        if ($routeId !== null) {
            $stmt->bindValue(':route_id', (int) $routeId, PDO::PARAM_INT);
        }

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_demand' => 0, 'total_collection' => 0];
        $row['balance'] = (float) $row['total_demand'] - (float) $row['total_collection'];

        return $row;
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
        return $this->getBillsSummary($route_id !== '' ? (int) $route_id : null);
    }
}
