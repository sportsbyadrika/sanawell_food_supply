<?php
class Role extends BaseModel
{
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM roles ORDER BY id');
        return $stmt->fetchAll();
    }
}
