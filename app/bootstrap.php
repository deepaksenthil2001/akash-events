<?php
/**
 * Akash Event Management - Application Bootstrap
 */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Load .env environment variables if file exists
if (file_exists(APP_ROOT . '/.env')) {
    $envLines = @file(APP_ROOT . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($envLines)) {
        foreach ($envLines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (str_contains($line, '=')) {
                [$key, $val] = explode('=', $line, 2);
                $key = trim($key);
                $val = trim($val, " \t\n\r\0\x0B\"'");
                putenv("{$key}={$val}");
                $_ENV[$key] = $val;
                $_SERVER[$key] = $val;
            }
        }
    }
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
