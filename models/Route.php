<?php
class Route extends BaseModel
{
    protected $table = "routes";

    public function allByAgency($agencyId)
    {
        $stmt = $this->db->prepare("SELECT * FROM routes WHERE agency_id = ?");
        $stmt->execute([$agencyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO routes (agency_id, name, type, description)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['agency_id'],
            $data['name'],
            $data['type'],
            $data['description']
        ]);
    }

    public function find(int $id): ?array
{
    $stmt = $this->db->prepare("
        SELECT * FROM routes 
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        'id' => $id
    ]);

    $result = $stmt->fetch();

    return $result ?: null;
}
public function update(int $id, array $data): void
{
    $stmt = $this->db->prepare("
        UPDATE routes
        SET name = ?, type = ?, description = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $data['name'],
        $data['type'],
        $data['description'],
        $id
    ]);
}

public function updateStatus(int $id, int $status): void
{
    $stmt = $this->db->prepare("
        UPDATE routes
        SET status = ?
        WHERE id = ?
    ");

    $stmt->execute([$status, $id]);
}
public function toggle(int $id): void
{
    $stmt = $this->db->prepare("
        UPDATE routes
        SET status = IF(status = 1, 0, 1)
        WHERE id = ?
    ");

    $stmt->execute([$id]);
}

}