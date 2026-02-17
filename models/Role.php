<?php

require_once __DIR__ . '/BaseModel.php';

class Role extends BaseModel
{
    public function all(): array
    {
        $stmt = $this->db->query("SELECT id, name FROM roles ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}