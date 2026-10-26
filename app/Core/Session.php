<?php
namespace App\Core;

class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        if (headers_sent()) {
            return;
        }

        $config = require dirname(__DIR__) . '/Config/config.php';
        $sessConfig = $config['session'] ?? [];

        session_name($sessConfig['name'] ?? 'akash_session');

        session_set_cookie_params([
            'lifetime' => $sessConfig['lifetime'] ?? 7200,
            'path' => $sessConfig['path'] ?? '/',
            'domain' => $sessConfig['domain'] ?? '',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_start();
        self::$started = true;
    }

    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }

    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        self::$started = false;
    }

    public static function flash(string $key, string $message): void
    {
        self::start();
        $_SESSION['flash'][$key][] = $message;
    }

    public static function getFlashes(): array
    {
        self::start();
        $flashes = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flashes;
    }
}
