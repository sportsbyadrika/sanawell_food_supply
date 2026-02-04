<?php
class UserType extends BaseModel
{
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM user_types ORDER BY id');
        return $stmt->fetchAll();
    }
}
