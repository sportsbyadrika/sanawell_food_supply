<?php


class RateModel {

     private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    // Get all rates of a product
   public function getByProduct($product_id) {
    $stmt = $this->db->prepare("
        SELECT r.*, ct.name AS customer_type_name
        FROM customer_type_rates r
        JOIN customer_categories ct 
            ON ct.id = r.customer_type_id
        WHERE r.product_id = ?
        ORDER BY r.valid_from DESC
    ");

    $stmt->execute([$product_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Add new rate
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO customer_type_rates
            (product_id, customer_type_id, rate, valid_from, valid_to)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['product_id'],
            $data['customer_type_id'],
            $data['rate'],
            $data['valid_from'],
            $data['valid_to']
        ]);
    }

    // Get all customer types
    public function getCustomerTypes() {
        return $this->db->query("SELECT * FROM customer_categories WHERE status = 1")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getProductsWithRate($customer_type_id)
{
    $stmt = $this->db->prepare("
        SELECT 
            p.id,
            p.name,
            p.variant,
            r.rate
        FROM products p
        JOIN customer_type_rates r 
            ON r.product_id = p.id
        WHERE r.customer_type_id = ?
    ");

    $stmt->execute([$customer_type_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getCurrentRate($productId, $customerTypeId)
{
    $stmt = $this->db->prepare("
        SELECT rate
        FROM customer_type_rates
        WHERE product_id = ?
        AND customer_type_id = ?
        ORDER BY valid_from DESC
        LIMIT 1
    ");

    $stmt->execute([$productId, $customerTypeId]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? (float)$result['rate'] : 0;
}
}