<?php

class Agency extends BaseModel
{
    /**
     * Count all agencies
     */
    public function countAll(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM agencies");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Count only active agencies
     */
    public function countActive(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM agencies WHERE status = :status"
        );
        $stmt->execute([
            'status' => 'active'
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Create a new agency
     */
   public function create(array $data): int
{
    
    $sql = "
        INSERT INTO agencies 
        (name,contact_number,contact_email, whatsapp_number, status)
        VALUES 
        (?, ?, ?, ?, ?)
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $data['name'],
        $data['contact_number'],
        $data['contact_email'],
        $data['whatsapp_number'],
        $data['status'] ?? 'active'
    ]);

    return (int) $this->db->lastInsertId();
}
    /**
     * Update agency status (activate / deactivate)
     */
    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare(
            "UPDATE agencies SET status = :status WHERE id = :id"
        );

        $stmt->execute([
            'status' => $status,
            'id' => $id,
        ]);
    }
    public function getAll(): array
{
    $stmt = $this->db->query(
        "SELECT id, name,contact_number, contact_email,whatsapp_number, status 
         FROM agencies 
         ORDER BY created_at DESC"
    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function countPending(): int
{
    $stmt = $this->db->query(
        "SELECT COUNT(*) FROM agencies WHERE status = 'pending'"
    );
    return (int) $stmt->fetchColumn();
}
public function findById(int $id): ?array
{
    $stmt = $this->db->prepare(
        "SELECT id, name, contact_number,contact_email,whatsapp_number, status 
         FROM agencies 
         WHERE id = :id 
         LIMIT 1"
    );

    $stmt->execute(['id' => $id]);
    $agency = $stmt->fetch(PDO::FETCH_ASSOC);

    return $agency ?: null;
}
}