<?php

class ChangeRequest extends BaseModel
{
public function getCustomer($id)
{
    $stmt = $this->db->prepare("
        SELECT c.*, r.name as route_name, r.type as route_type, cc.name AS category_name
        FROM customers c
        LEFT JOIN routes r ON r.id = c.route_id
         LEFT JOIN customer_categories cc ON cc.id = c.category_id
        WHERE c.id = ?
    ");

    $stmt->execute([$id]);

    return $stmt->fetch();
}
   public function getCustomerProducts($customerId)
{
    $stmt = $this->db->prepare("
    SELECT cp.*, 
           p.name AS product_name,
           p.variant
    FROM customer_products cp
    JOIN products p ON p.id = cp.product_id
    WHERE cp.customer_id = ?
");
    $stmt->execute([$customerId]);

    return $stmt->fetchAll();
}

    public function getRequests($customerId)
{
    $stmt = $this->db->prepare("
        SELECT cr.*,
               p.name,
               p.variant
        FROM change_requests cr
        JOIN products p ON p.id = cr.product_id
        WHERE cr.customer_id = ?
        ORDER BY cr.request_date DESC
    ");

    $stmt->execute([$customerId]);

    return $stmt->fetchAll();
}

 public function addRequest($customerId,$productId,$date,$requested,$normal)
{
    $stmt = $this->db->prepare("
        INSERT INTO change_requests
        (customer_id, product_id, request_date, requested_qty, normal_qty)
        VALUES (?, ?, ?, ?, ?)
    ");

    return $stmt->execute([
        $customerId,
        $productId,
        $date,
        $requested,
        $normal
    ]);
}
    public function cancelRequest($id)
    {
        $this->db->query("DELETE FROM change_requests WHERE id=?",[$id]);
    }

    public function getNormalQty($customerId, $productId)
{
    $stmt = $this->db->prepare("
        SELECT quantity
        FROM customer_products
        WHERE customer_id = ? AND product_id = ?
    ");

    $stmt->execute([$customerId, $productId]);

    $row = $stmt->fetch();

    if ($row) {
        return (int)$row['quantity'];
    }

    return 0;
}

public function getAllProducts()
{
    $stmt = $this->db->prepare("
        SELECT id, name, variant
        FROM products
        WHERE status = 'active'
        ORDER BY name
    ");

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}