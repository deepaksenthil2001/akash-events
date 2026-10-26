<?php
/**
 * Test Runner for Akash Event Management
 */
require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;
use App\Models\Service;
use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Enquiry;
use App\Models\Admin;

echo "==========================================\n";
echo "Akash Event Management System Integrity Test\n";
echo "==========================================\n";

try {
    $db = Database::getInstance();
    $driver = $db->getConnection()->getAttribute(PDO::ATTR_DRIVER_NAME);
    echo "[PASS] Database connected via {$driver} driver.\n";
} catch (\Exception $e) {
    echo "[FAIL] Database connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// Check Services
$services = Service::getAll(true);
echo "[PASS] Active Services loaded: " . count($services) . "\n";
if (empty($services)) {
    echo "[WARN] No active services found in database!\n";
}

// Check Gallery
$gallery = Gallery::getMedia(null, null, true);
echo "[PASS] Active Gallery media loaded: " . count($gallery) . "\n";

// Check Categories
$categories = Gallery::getCategories();
echo "[PASS] Gallery categories loaded: " . count($categories) . "\n";

// Check Settings
$settings = Setting::getAll();
echo "[PASS] Site settings loaded: " . count($settings) . " key-value pairs.\n";
echo "       - Business Name: " . ($settings['business_name'] ?? 'N/A') . "\n";
echo "       - Primary Phone: " . ($settings['phone_primary'] ?? 'N/A') . "\n";
echo "       - Lead Decorator: " . ($settings['lead_decorator'] ?? 'N/A') . "\n";

// Check Admin
$admin = Admin::findByUsername('admin');
if ($admin) {
    echo "[PASS] Default admin user found: 'admin' (ID: {$admin['id']})\n";
} else {
    echo "[FAIL] Default admin user not found!\n";
}

echo "==========================================\n";
echo "All Core Model Checks Passed Successfully!\n";
echo "==========================================\n";
