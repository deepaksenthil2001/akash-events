<?php
namespace App\Core;

class Auth
{
    private const SESSION_ADMIN_ID = '_admin_user_id';
    private const SESSION_ADMIN_USER = '_admin_user_data';

    public static function attempt(string $usernameOrEmail, string $password): bool
    {
        $db = Database::getInstance();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // Check rate limiting
        if (self::isRateLimited($ip)) {
            Session::flash('error', 'Too many failed login attempts. Please try again after 15 minutes.');
            return false;
        }

        $user = $db->fetch(
            "SELECT * FROM admins WHERE username = :u OR email = :e LIMIT 1",
            [':u' => $usernameOrEmail, ':e' => $usernameOrEmail]
        );

        if (!$user) {
            self::recordFailedAttempt($ip);
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            self::recordFailedAttempt($ip);
            return false;
        }

        // Login success - clear attempts and regenerate session ID
        self::clearAttempts($ip);
        Session::regenerate();

        // Update last login
        try {
            $db->execute("UPDATE admins SET last_login = NOW() WHERE id = :id", [':id' => $user['id']]);
        } catch (\Exception $e) {
            // non-fatal
        }

        // Store user in session (excluding password hash)
        unset($user['password_hash']);
        Session::set(self::SESSION_ADMIN_ID, $user['id']);
        Session::set(self::SESSION_ADMIN_USER, $user);

        return true;
    }

    public static function check(): bool
    {
        Session::start();
        return Session::has(self::SESSION_ADMIN_ID);
    }

    public static function user(): ?array
    {
        Session::start();
        return Session::get(self::SESSION_ADMIN_USER);
    }

    public static function id(): ?int
    {
        Session::start();
        return Session::get(self::SESSION_ADMIN_ID);
    }

    public static function logout(): void
    {
        Session::remove(self::SESSION_ADMIN_ID);
        Session::remove(self::SESSION_ADMIN_USER);
        Session::destroy();
    }

    public static function requireLogin(string $redirectPath = '/admin/login.php'): void
    {
        if (!self::check()) {
            Session::flash('error', 'Please log in to access the administration dashboard.');
            $config = require dirname(__DIR__) . '/Config/config.php';
            $basePath = $config['app']['base_path'] ?? '';
            header("Location: " . $basePath . $redirectPath);
            exit;
        }
    }

    private static function isRateLimited(string $ip): bool
    {
        $db = Database::getInstance();
        $config = require dirname(__DIR__) . '/Config/config.php';
        $maxAttempts = $config['security']['max_login_attempts'] ?? 5;
        $windowSeconds = $config['security']['lockout_duration'] ?? 900;

        try {
            // Delete old attempts beyond the window
            if ($db->getActiveDriver() === 'sqlite') {
                $db->execute("DELETE FROM login_attempts WHERE attempted_at < datetime('now', '-' || :secs || ' seconds')", [':secs' => $windowSeconds]);
                $count = (int) $db->fetchColumn(
                    "SELECT COUNT(*) FROM login_attempts WHERE ip_address = :ip AND attempted_at >= datetime('now', '-' || :secs || ' seconds')",
                    [':ip' => $ip, ':secs' => $windowSeconds]
                );
            } else {
                $db->execute("DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL :secs SECOND)", [':secs' => $windowSeconds]);
                $count = (int) $db->fetchColumn(
                    "SELECT COUNT(*) FROM login_attempts WHERE ip_address = :ip AND attempted_at >= DATE_SUB(NOW(), INTERVAL :secs SECOND)",
                    [':ip' => $ip, ':secs' => $windowSeconds]
                );
            }

            return $count >= $maxAttempts;
        } catch (\Exception $e) {
            return false;
        }
    }

    private static function recordFailedAttempt(string $ip): void
    {
        try {
            $db = Database::getInstance();
            $db->execute("INSERT INTO login_attempts (ip_address) VALUES (:ip)", [':ip' => $ip]);
        } catch (\Exception $e) {
            // non-fatal
        }
    }

    private static function clearAttempts(string $ip): void
    {
        try {
            $db = Database::getInstance();
            $db->execute("DELETE FROM login_attempts WHERE ip_address = :ip", [':ip' => $ip]);
        } catch (\Exception $e) {
            // non-fatal
        }
    }
}
