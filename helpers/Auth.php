<?php
class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function login(array $user): void
    {
        $_SESSION['user'] = $user;
        $_SESSION['last_activity'] = time();
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
    }
public static function hasRole(string $slug): bool
{
    if (!isset($_SESSION['user'])) {
        return false;
    }

    // Load DB config array
    $config = require __DIR__ . '/../config/database.php';

    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";

    try {
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        return false;
    }

    $stmt = $pdo->prepare("
        SELECT r.slug
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE u.id = ?
    ");

    $stmt->execute([$_SESSION['user']['id']]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    return $role && $role['slug'] === $slug;
}

public static function requireAgencyAdmin(): void
{
    if (!self::check()) {
        header('Location: index.php?route=login');
        exit;
    }

    // Allowed roles for agency-level access
    if (
        !self::hasRole('agency_admin') &&
        !self::hasRole('office_staff')
    ) {
        http_response_code(403);
        echo 'Access denied';
        exit;
    }
}

public static function requireDriver(): void
{
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?route=login");
        exit;
    }

    $config = require __DIR__ . '/../config/config.php';

    if ($_SESSION['user']['role_id'] != $config['roles']['DRIVER']['id']) {
        header("Location: index.php?route=login");
        exit;
    }
}
}
