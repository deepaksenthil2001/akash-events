<?php
$urls = [
    'http://127.0.0.1:8000/',
    'http://127.0.0.1:8000/about.php',
    'http://127.0.0.1:8000/services.php',
    'http://127.0.0.1:8000/service-details.php?slug=wedding-reception-decoration',
    'http://127.0.0.1:8000/gallery.php',
    'http://127.0.0.1:8000/contact.php',
    'http://127.0.0.1:8000/admin/login.php',
    'http://127.0.0.1:8000/public/assets/css/tokens.css',
    'http://127.0.0.1:8000/public/assets/images/brand/logo.jpg',
];

echo "Testing Live HTTP Server on 127.0.0.1:8000:\n";
foreach ($urls as $u) {
    $ctx = stream_context_create(['http' => ['ignore_errors' => true]]);
    $content = @file_get_contents($u, false, $ctx);
    $status = $http_response_header[0] ?? 'UNKNOWN';
    $len = strlen($content ?: '');
    echo "  {$u} -> {$status} ({$len} bytes)\n";
}
