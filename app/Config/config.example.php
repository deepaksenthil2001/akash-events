<?php
/**
 * Akash Event Management - Configuration Template
 * Copy this file to config.php and update with your local environment values.
 */

return [
    'app' => [
        'name' => 'Akash Event Management',
        'env' => 'production', // 'development' or 'production'
        'debug' => false,
        'base_path' => '',
        'base_url' => 'https://akasheventmanagement.com',
        'timezone' => 'Asia/Kolkata',
    ],

    'database' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'akash_event_management',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],

    'uploads' => [
        'max_image_size' => 15 * 1024 * 1024,
        'max_video_size' => 120 * 1024 * 1024,
        'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp'],
        'allowed_video_types' => ['video/mp4', 'video/quicktime'],
        'allowed_image_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
        'allowed_video_extensions' => ['mp4', 'mov'],
        'storage_path' => __DIR__ . '/../../storage/uploads',
        'public_uploads_path' => __DIR__ . '/../../public/uploads',
    ],

    'session' => [
        'name' => 'akash_session',
        'lifetime' => 7200,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ],

    'security' => [
        'max_login_attempts' => 5,
        'lockout_duration' => 900,
    ],
];
