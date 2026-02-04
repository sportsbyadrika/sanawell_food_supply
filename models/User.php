<?php
class User extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT users.*, roles.slug AS role_slug FROM users JOIN roles ON users.role_id = roles.id WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (agency_id, role_id, name, email, password_hash, status) VALUES (:agency_id, :role_id, :name, :email, :password_hash, :status)'
        );
        $stmt->execute([
            'agency_id' => $data['agency_id'],
            'role_id' => $data['role_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'status' => $data['status'],
        ]);

        return (int) $this->db->lastInsertId();
    }
}
