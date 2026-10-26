<?php
/**
 * Akash Event Management - CLI Built-in Server Router
 * Usage: php -S 127.0.0.1:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
$docRoot = __DIR__;

// 1. Static asset handling (CSS, JS, images, fonts)
$ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
$mimes = [
    'css'   => 'text/css; charset=UTF-8',
    'js'    => 'application/javascript; charset=UTF-8',
    'jpg'   => 'image/jpeg',
    'jpeg'  => 'image/jpeg',
    'png'   => 'image/png',
    'webp'  => 'image/webp',
    'gif'   => 'image/gif',
    'svg'   => 'image/svg+xml',
    'ico'   => 'image/x-icon',
    'woff'  => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf'   => 'font/ttf',
    'json'  => 'application/json',
    'xml'   => 'application/xml',
    'pdf'   => 'application/pdf',
];

if (isset($mimes[$ext])) {
    $possiblePaths = [
        $docRoot . $uri,
        $docRoot . '/public' . $uri,
        $docRoot . '/public/' . preg_replace('#^/public/#', '', $uri),
    ];
    foreach ($possiblePaths as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            header('Content-Type: ' . $mimes[$ext]);
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: public, max-age=3600');
            readfile($filePath);
            return true;
        }
    }
}

// 3. Admin routes
if (strpos($uri, '/admin') === 0) {
    $adminFile = $docRoot . $uri;
    if (is_dir($adminFile)) {
        $adminFile = rtrim($adminFile, '/') . '/index.php';
    } elseif (!str_ends_with($adminFile, '.php')) {
        $adminFile .= '.php';
    }
    if (file_exists($adminFile)) {
        require $adminFile;
        return true;
    }
}

// 4. Public PHP page routing
$cleanRoute = trim($uri, '/');
if ($cleanRoute === '' || $cleanRoute === 'index.php') {
    require $docRoot . '/public/index.php';
    return true;
}

if (!str_ends_with($cleanRoute, '.php')) {
    $cleanRoute .= '.php';
}

if (file_exists($docRoot . '/public/' . $cleanRoute)) {
    require $docRoot . '/public/' . $cleanRoute;
    return true;
}

if (file_exists($docRoot . '/' . $cleanRoute)) {
    require $docRoot . '/' . $cleanRoute;
    return true;
}

// 5. 404 handler
http_response_code(404);
require $docRoot . '/public/404.php';
return true;
