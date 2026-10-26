<?php
/**
 * Akash Event Management - Application Bootstrap
 */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Error reporting based on environment
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set default timezone
date_default_timezone_set('Asia/Kolkata');

// Register PSR-4 Autoloader for "App\" namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_ROOT . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Start session
\App\Core\Session::start();

// Global helper shortcuts
if (!function_exists('e')) {
    function e(?string $value): string {
        return \App\Helpers\View::e($value);
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        return \App\Helpers\View::url($path);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return \App\Helpers\View::asset($path);
    }
}

if (!function_exists('setting')) {
    function setting(string $key, string $default = ''): string {
        return \App\Helpers\View::setting($key, $default);
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return \App\Core\Csrf::field();
    }
}

if (!function_exists('flash')) {
    function flash(string $key, string $message): void {
        \App\Core\Session::flash($key, $message);
    }
}
