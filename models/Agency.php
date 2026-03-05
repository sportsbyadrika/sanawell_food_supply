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

    public function getByStatus($status)
{
    $stmt = $this->db->prepare("SELECT * FROM agencies WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchAll();
}

public function filter($status = null, $from = null, $to = null, $agencyAdminRoleId)
{
    $sql = "
        SELECT 
            a.*,
            (
                SELECT u.last_login
                FROM users u
                WHERE u.agency_id = a.id
                AND u.role_id = :role_id
                AND u.last_login IS NOT NULL
                ORDER BY u.last_login DESC
                LIMIT 1
            ) AS last_login
        FROM agencies a
        WHERE 1=1
    ";

    $params = [
        ':role_id' => $agencyAdminRoleId
    ];

    if (!empty($status)) {
        $sql .= " AND a.status = :status";
        $params[':status'] = $status;
    }

    if (!empty($from) && !empty($to)) {
        $sql .= " AND DATE(a.created_at) BETWEEN :from AND :to";
        $params[':from'] = $from;
        $params[':to']   = $to;
    }

    $sql .= " ORDER BY a.created_at DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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

 public function getAll($agencyAdminRoleId)
{
    $stmt = $this->db->prepare("
        SELECT 
            a.*,
            (
                SELECT u.last_login
                FROM users u
                WHERE u.agency_id = a.id
                  AND u.role_id = :role_id
                ORDER BY u.last_login DESC
                LIMIT 1
            ) AS last_login
        FROM agencies a
        ORDER BY a.created_at DESC
    ");

    $stmt->execute(['role_id' => $agencyAdminRoleId]);

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