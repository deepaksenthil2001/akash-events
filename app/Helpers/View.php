<?php
namespace App\Helpers;

use App\Core\Database;
use App\Core\Session;

class View
{
    private static ?array $settingsCache = null;

    public static function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public static function url(string $path = ''): string
    {
        $config = require dirname(__DIR__) . '/Config/config.php';
        $base = str_replace('\\', '/', rtrim($config['app']['base_path'] ?? '', '/\\'));
        $path = '/' . ltrim(str_replace('\\', '/', $path), '/');
        
        // If path points to root, handle cleanly
        if ($path === '/' || $path === '') {
            return $base !== '' ? $base . '/' : '/';
        }
        
        return $base . $path;
    }

    public static function asset(string $path): string
    {
        $config = require dirname(__DIR__) . '/Config/config.php';
        $base = str_replace('\\', '/', rtrim($config['app']['base_path'] ?? '', '/\\'));
        
        // Clean path to always use forward slashes
        $cleanPath = '/' . ltrim(str_replace('\\', '/', $path), '/');
        
        // If file exists under public directory
        $publicDir = str_replace('\\', '/', dirname(__DIR__, 2)) . '/public';
        if (file_exists($publicDir . $cleanPath)) {
            // Check if document root is already pointing to public/
            if (isset($_SERVER['DOCUMENT_ROOT']) && realpath($_SERVER['DOCUMENT_ROOT']) === realpath($publicDir)) {
                return $cleanPath;
            }
            return ($base !== '' ? $base : '') . '/public' . $cleanPath;
        }

        return ($base !== '' ? $base : '') . $cleanPath;
    }

    public static function setting(string $key, string $default = ''): string
    {
        if (self::$settingsCache === null) {
            try {
                $db = Database::getInstance();
                $rows = $db->fetchAll("SELECT setting_key, setting_value FROM site_settings");
                self::$settingsCache = [];
                foreach ($rows as $r) {
                    self::$settingsCache[$r['setting_key']] = $r['setting_value'];
                }
            } catch (\Exception $e) {
                self::$settingsCache = [];
            }
        }

        return self::$settingsCache[$key] ?? $default;
    }

    public static function formatDate(?string $date, string $format = 'M d, Y'): string
    {
        if (!$date) return 'N/A';
        try {
            $dt = new \DateTime($date);
            return $dt->format($format);
        } catch (\Exception $e) {
            return $date;
        }
    }

    public static function truncate(string $text, int $limit = 120, string $end = '...'): string
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }
        return mb_substr($text, 0, $limit) . $end;
    }

    public static function active(string $current, string $target, string $className = 'active'): string
    {
        return $current === $target ? $className : '';
    }
}
