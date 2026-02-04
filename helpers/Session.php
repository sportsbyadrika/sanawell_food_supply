<?php
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        self::enforceTimeout();
    }

    private static function enforceTimeout(): void
    {
        $config = require __DIR__ . '/../config/config.php';
        $timeout = $config['session_timeout'];
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
            session_unset();
            session_destroy();
        }
        $_SESSION['last_activity'] = time();
    }
}
