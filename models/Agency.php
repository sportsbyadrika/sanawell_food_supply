<?php
class Agency extends BaseModel
{
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM agencies ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO agencies (name, contact_email, status) VALUES (:name, :contact_email, :status)');
        $stmt->execute([
            'name' => $data['name'],
            'contact_email' => $data['contact_email'],
            'status' => $data['status'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE agencies SET status = :status WHERE id = :id');
        $stmt->execute([
            'status' => $status,
            'id' => $id,
        ]);
    }
}
