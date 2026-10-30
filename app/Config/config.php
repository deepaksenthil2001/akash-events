<?php
/**
 * Akash Event Management - Application Configuration
 */

// Define application root paths
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__, 2));
}

// Auto-detect base URL
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$protocol = $isHttps ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir = str_replace('\\', '/', dirname($scriptName));

// Compute base path relative to project root
$basePath = '';
if (strpos($scriptName, '/public/') !== false) {
    $basePath = substr($scriptName, 0, strpos($scriptName, '/public/'));
} elseif (strpos($scriptName, '/admin/') !== false) {
    $basePath = substr($scriptName, 0, strpos($scriptName, '/admin/'));
} else {
    $basePath = rtrim($scriptDir, '/\\');
    if ($basePath === '/' || $basePath === '\\' || $basePath === '.' || $basePath === '') {
        $basePath = '';
    }
}

$basePath = str_replace('\\', '/', $basePath);
$basePath = rtrim($basePath, '/');
if ($basePath === '/' || $basePath === '.') {
    $basePath = '';
}

$baseUrl = $protocol . $host . $basePath;

return [
    'app' => [
        'name' => 'Akash Event Management',
        'env' => 'development', // 'development' or 'production'
        'debug' => true,
        'base_path' => $basePath,
        'base_url' => $baseUrl,
        'timezone' => 'Asia/Kolkata',
    ],

    'database' => [
        'driver' => getenv('DB_DRIVER') ?: 'mysql', // 'mysql' or 'sqlite'
        'host' => getenv('DB_HOST') ?: 'localhost', // 'localhost' for Hostinger or '127.0.0.1'
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'database' => getenv('DB_NAME') ?: 'u663442222_akash',
        'username' => getenv('DB_USER') ?: 'u663442222_akash',
        'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'AkashHostinger2026!',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
        // Local SQLite fallback path for standalone dev testing if MySQL service is offline
        'sqlite_path' => APP_ROOT . '/storage/database.sqlite',
    ],

    'uploads' => [
        'max_image_size' => 15 * 1024 * 1024, // 15 MB
        'max_video_size' => 120 * 1024 * 1024, // 120 MB
        'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp'],
        'allowed_video_types' => ['video/mp4', 'video/quicktime'],
        'allowed_image_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
        'allowed_video_extensions' => ['mp4', 'mov'],
        'storage_path' => APP_ROOT . '/storage/uploads',
        'public_uploads_path' => APP_ROOT . '/public/uploads',
    ],

    'session' => [
        'name' => 'akash_session',
        'lifetime' => 7200, // 2 hours
        'path' => '/',
        'domain' => '',
        'secure' => false, // Set true on HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ],

    'security' => [
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutes
    ],
];
