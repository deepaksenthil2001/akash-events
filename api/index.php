<?php
/**
 * Vercel Serverless PHP Entry Point
 * Routes incoming requests to public files or controllers
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

// Normalize path if browser requested with /public/ prefix
$normalizedUri = $uri;
if (str_starts_with($uri, '/public/')) {
    $normalizedUri = substr($uri, 7);
}

// 1. If admin route requested: admin files are located in root admin/ directory
if (str_starts_with($normalizedUri, '/admin')) {
    $adminFile = __DIR__ . '/..' . $normalizedUri;
    if (is_dir($adminFile)) {
        $adminFile = rtrim($adminFile, '/') . '/index.php';
    } elseif (!file_exists($adminFile) && file_exists($adminFile . '.php')) {
        $adminFile = $adminFile . '.php';
    }
    if (file_exists($adminFile)) {
        chdir(dirname($adminFile));
        require $adminFile;
        exit;
    }
}

// 2. Check if static asset or public file exists directly in public/
$publicFile = __DIR__ . '/../public' . $normalizedUri;

if ($normalizedUri !== '/' && $normalizedUri !== '' && file_exists($publicFile) && !is_dir($publicFile)) {
    $ext = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
    
    // If it's a PHP file, execute it
    if ($ext === 'php') {
        chdir(dirname($publicFile));
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

// 3. Route extensionless URLs in public/ (e.g. /services -> /public/services.php)
$cleanPath = trim($normalizedUri, '/');
if (!empty($cleanPath) && file_exists(__DIR__ . '/../public/' . $cleanPath . '.php')) {
    chdir(__DIR__ . '/../public');
    require __DIR__ . '/../public/' . $cleanPath . '.php';
    exit;
}

// 4. Default fallback to public homepage
$_SERVER['SCRIPT_NAME'] = '/index.php';
chdir(__DIR__ . '/../public');
require __DIR__ . '/../public/index.php';
