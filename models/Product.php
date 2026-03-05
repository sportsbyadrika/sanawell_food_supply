<?php
class Product extends BaseModel
{
   public function countByAgency($agencyId)
{
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE agency_id = ?");
    $stmt->execute([$agencyId]);
    return $stmt->fetchColumn();
}
public function getByAgency(int $agencyId): array
{
    $stmt = $this->db->prepare(
        "SELECT * FROM products 
         WHERE agency_id = ? 
         ORDER BY created_at DESC"
    );

    $stmt->execute([$agencyId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO products (agency_id, name, description,image, variant, status) VALUES (:agency_id, :name, :description,:image, :variant, :status)');
        $stmt->execute([
            'agency_id' => $data['agency_id'],
            'name' => $data['name'],
            'description' => $data['description'],
             'image' => $data['image'],
            'variant' => $_POST['variant'] ?? '',
            'status' => $data['status'],
        ]);

        return (int) $this->db->lastInsertId();
    }
    public function find(int $id)
{
    $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function update(int $id, array $data): void
{
    $stmt = $this->db->prepare("
        UPDATE products 
        SET name = ?, description = ?, image= ?, variant = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $data['name'],
        $data['description'],
        $data['image'],
        $data['variant'],
        $id
    ]);
}

public function allActive(): array
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM products
        WHERE status = 'active'
        ORDER BY name ASC
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
