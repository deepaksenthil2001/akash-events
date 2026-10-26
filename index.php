<?php
/**
 * Akash Event Management - Entry Router
 * Supports direct execution from root or public/ folder in Apache / XAMPP / PHP built-in server.
 */

// If requested URI matches public asset, pass through or forward
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));

if ($uri !== '/' && $uri !== '' && file_exists(__DIR__ . '/public' . $uri)) {
    return false; // serve requested resource as-is in CLI server
}

require_once __DIR__ . '/public/index.php';
