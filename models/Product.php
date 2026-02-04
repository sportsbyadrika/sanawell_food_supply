<?php
class Product extends BaseModel
{
    public function allByAgency(int $agencyId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE agency_id = :agency_id ORDER BY created_at DESC');
        $stmt->execute(['agency_id' => $agencyId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO products (agency_id, name, description, status) VALUES (:agency_id, :name, :description, :status)');
        $stmt->execute([
            'agency_id' => $data['agency_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => $data['status'],
        ]);

        return (int) $this->db->lastInsertId();
    }
}
