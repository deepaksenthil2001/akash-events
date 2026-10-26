<?php
/**
 * Vercel Serverless PHP Entry Point
 * Routes incoming requests to public files or controllers
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

// Check if static asset or public file exists directly
$publicFile = __DIR__ . '/../public' . $uri;

if ($uri !== '/' && file_exists($publicFile) && !is_dir($publicFile)) {
    $ext = pathinfo($publicFile, PATHINFO_EXTENSION);
    
    // If it's a PHP file, execute it
    if ($ext === 'php') {
        require $publicFile;
        exit;
    }
    
    // Otherwise serve static asset
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'mp4'  => 'video/mp4',
        'woff2'=> 'font/woff2',
        'woff' => 'font/woff',
        'ttf'  => 'font/ttf'
    ];
    if (isset($mimes[$ext])) {
        header("Content-Type: {$mimes[$ext]}");
    }
    readfile($publicFile);
    exit;
}

// If admin directory requested
if (str_starts_with($uri, '/admin')) {
    $adminFile = __DIR__ . '/../public' . $uri;
    if (is_dir($adminFile)) {
        $adminFile = rtrim($adminFile, '/') . '/index.php';
    }
    if (file_exists($adminFile)) {
        require $adminFile;
        exit;
    }
}

// Route extensionless URLs (e.g. /services -> /public/services.php)
$cleanPath = trim($uri, '/');
if (!empty($cleanPath) && file_exists(__DIR__ . '/../public/' . $cleanPath . '.php')) {
    require __DIR__ . '/../public/' . $cleanPath . '.php';
    exit;
}

// Default fallback to public homepage
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/../public/index.php';
