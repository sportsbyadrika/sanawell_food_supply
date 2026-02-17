<?php

class DeliveryModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getDeliveriesByDriver($driverId)
    {
        $sql = "
            SELECT 
                d.id,
                d.status,
                d.delivery_date,
                o.order_number,
                c.name AS customer_name,
                c.address
            FROM deliveries d
            JOIN orders o ON o.id = d.order_id
            JOIN customers c ON c.id = o.customer_id
            WHERE d.driver_id = :driver_id
            ORDER BY d.delivery_date ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'driver_id' => $driverId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}