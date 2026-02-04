<?php
class Csrf
{
    public static function token(): string
    {
        $config = require __DIR__ . '/../config/config.php';
        $tokenName = $config['csrf_token_name'];

        if (empty($_SESSION[$tokenName])) {
            $_SESSION[$tokenName] = bin2hex(random_bytes(32));
        }

        return $_SESSION[$tokenName];
    }

    public static function verify(?string $token): bool
    {
        $config = require __DIR__ . '/../config/config.php';
        $tokenName = $config['csrf_token_name'];
        return isset($_SESSION[$tokenName]) && hash_equals($_SESSION[$tokenName], (string) $token);
    }
}
