<?php
/**
 * Akash Event Management - Database Provisioning & Admin Utility
 * Run via CLI: php scripts/create_admin.php [username] [password] [email]
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;
use App\Models\Admin;

echo "========================================================\n";
echo " Akash Event Management - Setup & Admin Provisioning \n";
echo "========================================================\n\n";

try {
    $db = Database::getInstance();
    $driver = $db->getActiveDriver();
    echo "Connected to database using driver: [{$driver}]\n";

    // If SQLite driver active, auto-initialize schema and seed data
    if ($driver === 'sqlite') {
        echo "Initializing SQLite schema & tables...\n";
        $pdo = $db->getPdo();

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                full_name TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT 'superadmin',
                last_login TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS site_settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                setting_key TEXT NOT NULL UNIQUE,
                setting_value TEXT NULL,
                setting_group TEXT NOT NULL DEFAULT 'general',
                description TEXT NULL,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS services (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                slug TEXT NOT NULL UNIQUE,
                title TEXT NOT NULL,
                short_description TEXT NOT NULL,
                full_description TEXT NOT NULL,
                cover_image TEXT NOT NULL,
                banner_image TEXT NULL,
                features_list TEXT NULL,
                display_order INTEGER NOT NULL DEFAULT 0,
                is_published INTEGER NOT NULL DEFAULT 1,
                is_featured INTEGER NOT NULL DEFAULT 0,
                meta_title TEXT NULL,
                meta_description TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS gallery_categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                slug TEXT NOT NULL UNIQUE,
                name TEXT NOT NULL,
                description TEXT NULL,
                display_order INTEGER NOT NULL DEFAULT 0,
                is_active INTEGER NOT NULL DEFAULT 1,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS gallery_media (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NULL,
                title TEXT NOT NULL,
                caption TEXT NULL,
                media_type TEXT NOT NULL DEFAULT 'image',
                file_path TEXT NOT NULL,
                mobile_file_path TEXT NULL,
                thumbnail_path TEXT NULL,
                is_featured INTEGER NOT NULL DEFAULT 0,
                is_concept INTEGER NOT NULL DEFAULT 0,
                display_order INTEGER NOT NULL DEFAULT 0,
                is_published INTEGER NOT NULL DEFAULT 1,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES gallery_categories(id) ON DELETE SET NULL
            );

            CREATE TABLE IF NOT EXISTS enquiries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer_name TEXT NOT NULL,
                phone TEXT NOT NULL,
                email TEXT NULL,
                event_type TEXT NOT NULL,
                preferred_date TEXT NULL,
                event_location TEXT NULL,
                guest_count TEXT NULL,
                message TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT 'new',
                admin_notes TEXT NULL,
                ip_address TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS login_attempts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ip_address TEXT NOT NULL,
                attempted_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
        ");

        echo "Tables verified.\n";
    }

    // Check if initial admin exists, if not create
    $username = $argv[1] ?? 'admin';
    $password = $argv[2] ?? 'Admin@Akash2026!';
    $email = $argv[3] ?? 'akasheventmanagement87@gmail.com';
    $name = 'P. Manobalan';

    $existing = Admin::getByUsername($username);
    if ($existing) {
        Admin::updatePassword((int) $existing['id'], $password);
        echo "Admin user '{$username}' already exists. Password successfully reset to provided password.\n";
    } else {
        $adminId = Admin::create([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'full_name' => $name,
            'role' => 'superadmin'
        ]);
        echo "Admin user '{$username}' created successfully with ID: {$adminId}!\n";
    }

    // Seed default settings and services if empty
    $settingsCount = (int) $db->fetchColumn("SELECT COUNT(*) FROM site_settings");
    if ($settingsCount === 0) {
        echo "Seeding default verified business settings...\n";
        require_once __DIR__ . '/seed_data.php';
    }

    echo "\n--------------------------------------------------------\n";
    echo " Setup complete!\n";
    echo " Admin Login URL : /admin/login.php\n";
    echo " Username        : {$username}\n";
    echo " Password        : {$password}\n";
    echo "--------------------------------------------------------\n\n";

} catch (\Exception $e) {
    echo "Error during setup: " . $e->getMessage() . "\n";
    exit(1);
}
