<?php
$_SERVER['SCRIPT_NAME'] = '/index.php';
$config = require __DIR__ . '/../app/Config/config.php';
echo "base_path for /index.php: [" . $config['app']['base_path'] . "]\n";
require_once __DIR__ . '/../app/bootstrap.php';
echo "asset path: [" . \App\Helpers\View::asset('assets/css/tokens.css') . "]\n";
