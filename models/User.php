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
    public function getRoles(): array
{
    $stmt = $this->db->query("SELECT id, name, slug FROM roles ORDER BY id ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function agencyAdminExists(int $agencyId): bool
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) 
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE u.agency_id = :agency_id
        AND r.slug = 'AGENCY_ADMIN'
    ");

    $stmt->execute([
        'agency_id' => $agencyId
    ]);

    return (int)$stmt->fetchColumn() > 0;
}
public function countByAgencyAndRole($agencyId, $roleId)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*)
        FROM users
        WHERE agency_id = ?
        AND role_id = ?
    ");
    $stmt->execute([$agencyId, $roleId]);
    return $stmt->fetchColumn();
}

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (agency_id, role_id, name, email,mobile, password_hash, status) VALUES (:agency_id, :role_id, :name, :email,:mobile, :password_hash, :status)'
        );
        $stmt->execute([
            'agency_id' => $data['agency_id'],
            'role_id' => $data['role_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password_hash' => $data['password_hash'],
            'status' => $data['status'],
        ]);

        return (int) $this->db->lastInsertId();
    }
    public function emailExists(string $email): bool
{
    $stmt = $this->db->prepare(
        "SELECT id FROM users WHERE email = ? LIMIT 1"
    );
    $stmt->execute([$email]);
    return (bool) $stmt->fetch();
}
public function updatePasswordAndUnlock(int $userId, string $passwordHash): void
{
    $stmt = $this->db->prepare(
        "UPDATE users SET password_hash = ?, first_login = 0 WHERE id = ?"
    );
    $stmt->execute([$passwordHash, $userId]);
}

public function updateAdminPassword(int $agencyId, string $passwordHash): bool
{
    $stmt = $this->db->prepare("
        UPDATE users 
        SET password_hash = ?, first_login = 1
        WHERE agency_id = ? 
        AND role_id = ?
        LIMIT 1
    ");

    $config = require __DIR__ . '/../config/config.php';
    $agencyAdminRoleId = $config['roles']['AGENCY_ADMIN']['id'];

    return $stmt->execute([$passwordHash, $agencyId, $agencyAdminRoleId]);
}

public function updateLastLogin(int $id): void
{
    $stmt = $this->db->prepare(
        "UPDATE users SET last_login = NOW() WHERE id = :id"
    );

    $stmt->execute(['id' => $id]);
}
public function getAgencyStaffUsers(
    int $agencyId,
    int $officeStaffRoleId,
    int $driverRoleId
): array {
    $sql = "
    SELECT u.*, r.name AS role_name, r.slug
    FROM users u
    JOIN roles r ON u.role_id = r.id
    WHERE u.agency_id = ?
    AND u.role_id IN (?, ?)
    ORDER BY u.id DESC
";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$agencyId, $officeStaffRoleId, $driverRoleId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getById(int $id, int $agencyId)
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function getStaffRoles(): array
{
    $stmt = $this->db->prepare("
        SELECT id, name
        FROM roles
        WHERE id IN (3, 4)
        ORDER BY id ASC
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function isValidStaffRole(int $roleId): bool
{
    $stmt = $this->db->prepare("
        SELECT id 
        FROM roles 
        WHERE id = ?
        AND name IN ('Driver', 'Office Staff')
    ");

    $stmt->execute([$roleId]);

    return (bool)$stmt->fetch();
}
public function updateUser(int $id, array $data): bool
{
    $stmt = $this->db->prepare("
        UPDATE users
        SET name = ?,
            email = ?,
            mobile = ?,
            role_id = ?,
            status = ?
        WHERE id = ?
        LIMIT 1
    ");

    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['mobile'],
        $data['role_id'],
        $data['status'],
        $id
    ]);
}

public function saveResetToken(int $id, string $token, string $expires): void
{
    $stmt = $this->db->prepare("
        UPDATE users
        SET password_reset_token = ?, password_reset_expires = ?
        WHERE id = ?
    ");
    $stmt->execute([$token, $expires, $id]);
}

public function findByResetToken(string $token): ?array
{
    $stmt = $this->db->prepare("
        SELECT * FROM users
        WHERE password_reset_token = ?
        LIMIT 1
    ");
    $stmt->execute([$token]);
    return $stmt->fetch() ?: null;
}

public function updatePasswordAndClearToken(int $id, string $hash): void
{
    $stmt = $this->db->prepare("
        UPDATE users
        SET password_hash = ?,
            password_reset_token = NULL,
            password_reset_expires = NULL,
            first_login = 0
        WHERE id = ?
    ");
    $stmt->execute([$hash, $id]);
}

public function getDriversByAgency($agencyId)
{
    $stmt = $this->db->prepare("
        SELECT u.id, u.name
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE r.name = 'Driver'
        AND u.agency_id = ?
        AND u.status = 1
        ORDER BY u.name
    ");

    $stmt->execute([$agencyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
