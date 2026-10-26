<?php
$assets = [
    'http://127.0.0.1:8000/public/assets/css/tokens.css',
    'http://127.0.0.1:8000/public/assets/css/reset.css',
    'http://127.0.0.1:8000/public/assets/css/global.css',
    'http://127.0.0.1:8000/public/assets/css/components.css',
    'http://127.0.0.1:8000/public/assets/css/responsive.css',
    'http://127.0.0.1:8000/public/assets/css/pages/home.css',
    'http://127.0.0.1:8000/public/assets/images/brand/logo.jpg',
    'http://127.0.0.1:8000/public/assets/images/hero/hero-desktop.jpg',
];

echo "Checking Asset HTTP Responses:\n";
foreach ($assets as $url) {
    $ctx = stream_context_create(['http' => ['ignore_errors' => true]]);
    $content = file_get_contents($url, false, $ctx);
    $status = $http_response_header[0] ?? 'NO STATUS';
    $ct = '';
    foreach ($http_response_header as $h) {
        if (stripos($h, 'Content-Type:') === 0) {
            $ct = trim(substr($h, 13));
        }
    }
    echo basename($url) . " -> {$status} | Content-Type: {$ct} | Size: " . strlen($content) . " bytes\n";
}
