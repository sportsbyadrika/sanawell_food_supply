<?php
class ProductRate extends BaseModel
{
    public function allByProduct(int $productId): array
    {
        $stmt = $this->db->prepare(
            'SELECT product_rates.*, user_types.name AS user_type_name FROM product_rates JOIN user_types ON product_rates.user_type_id = user_types.id WHERE product_id = :product_id'
        );
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function upsert(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO product_rates (product_id, user_type_id, rate) VALUES (:product_id, :user_type_id, :rate)
             ON DUPLICATE KEY UPDATE rate = VALUES(rate)'
        );
        $stmt->execute([
            'product_id' => $data['product_id'],
            'user_type_id' => $data['user_type_id'],
            'rate' => $data['rate'],
        ]);
    }
}
