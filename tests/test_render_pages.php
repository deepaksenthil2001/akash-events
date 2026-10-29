<?php
/**
 * Test Page Rendering - Akash Event Management
 */

require_once __DIR__ . '/../app/bootstrap.php';

$pages = [
    'Home Page' => '/public/index.php',
    'About Page' => '/public/about.php',
    'Services Page' => '/public/services.php',
    'Service Details Page' => '/public/service-details.php',
    'Gallery Page' => '/public/gallery.php',
    'Contact Page' => '/public/contact.php',
    'Admin Login Page' => '/admin/login.php',
    'Admin Dashboard' => '/admin/dashboard.php',
    'Admin Events List' => '/admin/gallery/index.php',
    'Admin Event Upload' => '/admin/gallery/upload.php',
    'Admin Event Categories' => '/admin/gallery/categories.php',
    'Admin Business Settings' => '/admin/settings/index.php',
    'Admin Hero & Content' => '/admin/settings/hero.php',
    'Admin About Content' => '/admin/settings/about.php',
    'Admin User Management' => '/admin/users/index.php',
];

echo "==========================================\n";
echo "Testing Page Output and Execution\n";
echo "==========================================\n";

$_SERVER['HTTP_HOST'] = '127.0.0.1:8000';
$_SERVER['SERVER_PORT'] = '8000';
$_SERVER['REQUEST_METHOD'] = 'GET';

$testFailuresCount = 0;

foreach ($pages as $title => $relativePath) {
    $_SERVER['SCRIPT_NAME'] = $relativePath;
    $_SERVER['REQUEST_URI'] = $relativePath;
    if (strpos($relativePath, '/admin/') !== false && $title !== 'Admin Login Page') {
        \App\Core\Session::set('_admin_user_id', 1);
        \App\Core\Session::set('_admin_user_data', [
            'id' => 1,
            'username' => 'admin',
            'full_name' => 'P. Manobalan',
            'role' => 'superadmin'
        ]);
    } else {
        \App\Core\Session::remove('_admin_user_id');
        \App\Core\Session::remove('_admin_user_data');
    }

    if ($title === 'Service Details Page') {
        $_GET['slug'] = 'wedding-reception-decoration';
    } else {
        unset($_GET['slug']);
    }

    ob_start();
    try {
        require __DIR__ . '/..' . $relativePath;
        $output = ob_get_clean();
        $len = strlen($output);
        if ($len > 500) {
            echo "[PASS] {$title} rendered successfully ({$len} bytes).\n";
        } else {
            echo "[WARN] {$title} output was unusually small ({$len} bytes).\n";
        }
    } catch (\Throwable $e) {
        ob_end_clean();
        echo "[FAIL] {$title} failed with exception: " . $e->getMessage() . " on line " . $e->getLine() . "\n";
        $testFailuresCount++;
    }
}

echo "==========================================\n";
if ($testFailuresCount === 0) {
    echo "All Pages Rendered Cleanly Without Exceptions!\n";
} else {
    echo "Completed with {$testFailuresCount} errors.\n";
}
echo "==========================================\n";
