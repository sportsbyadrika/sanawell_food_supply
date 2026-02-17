<?php

require_once __DIR__ . '/../helpers/Database.php';

class RouteCustomer
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connection();
    }

    public function assign($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO route_customers
            (route_id, customer_id, delivery_order)
            VALUES
            (:route_id, :customer_id, :delivery_order)
        ");

        return $stmt->execute($data);
    }

    public function customersByRoute($route_id)
    {
        $stmt = $this->db->prepare("
            SELECT rc.*, c.name
            FROM route_customers rc
            JOIN customers c ON rc.customer_id = c.id
            WHERE rc.route_id = :route_id
            ORDER BY rc.delivery_order
        ");

        $stmt->execute(['route_id' => $route_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
