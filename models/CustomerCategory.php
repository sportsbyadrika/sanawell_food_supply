<?php

class CustomerCategory
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function allByAgency(int $agencyId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM customer_categories 
             WHERE agency_id = :aid 
             ORDER BY name"
        );
        $stmt->execute(['aid' => $agencyId]);
        return $stmt->fetchAll();
    }

   public function create(array $data): bool
{
    $sql = "INSERT INTO customer_categories 
            (agency_id, name, description,status)
            VALUES (:agency_id, :name, :description,1)";

    $stmt = Database::connection()->prepare($sql);
    return $stmt->execute([
        'agency_id'   => $data['agency_id'],
        'name'        => $data['name'],
        'description' => $data['description'],
        
    ]);
}


 public function toggle(int $id): bool
{
    $sql = "UPDATE customer_categories 
            SET status = CASE 
                WHEN status = 'active' THEN 'inactive'
                ELSE 'active'
            END
            WHERE id = :id";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'id' => $id
    ]);
}
    public function find(int $id): ?array
{
    $stmt = $this->db->prepare("
        SELECT * FROM customer_categories
        WHERE id = :id
    ");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch();

    return $result ?: null;
}

    public function update(int $id, array $data): void
{
    $stmt = $this->db->prepare("
        UPDATE customer_categories
        SET name = :name,
            description = :description
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => $id,
        'name' => $data['name'],
        'description' => $data['description']
    ]);
}
}