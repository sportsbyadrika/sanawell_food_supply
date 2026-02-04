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

    public static function hasRole(string $role): bool
    {
        return self::check() && ($_SESSION['user']['role_slug'] ?? '') === $role;
    }
}
