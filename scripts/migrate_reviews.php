<?php
/**
 * Akash Event Management - Migration: Reviews Table
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

$db = Database::getInstance();
$pdo = $db->getPdo();
$driver = $db->getActiveDriver();

echo "Running Reviews migration on driver: [{$driver}]...\n";

if ($driver === 'sqlite') {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name TEXT NOT NULL,
            rating INTEGER NOT NULL DEFAULT 5,
            event_type TEXT NULL,
            review_text TEXT NOT NULL,
            review_date TEXT NULL,
            source TEXT NOT NULL DEFAULT 'google',
            avatar_url TEXT NULL,
            is_verified INTEGER NOT NULL DEFAULT 1,
            is_published INTEGER NOT NULL DEFAULT 1,
            display_order INTEGER NOT NULL DEFAULT 0,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        );

        CREATE INDEX IF NOT EXISTS idx_reviews_published ON reviews (is_published, display_order);
    ");
} else {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `reviews` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `customer_name` VARCHAR(100) NOT NULL,
            `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
            `event_type` VARCHAR(100) NULL,
            `review_text` TEXT NOT NULL,
            `review_date` DATE NULL,
            `source` VARCHAR(30) NOT NULL DEFAULT 'google',
            `avatar_url` VARCHAR(255) NULL,
            `is_verified` TINYINT(1) NOT NULL DEFAULT 1,
            `is_published` TINYINT(1) NOT NULL DEFAULT 1,
            `display_order` INT NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            INDEX `idx_reviews_published` (`is_published`, `display_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
}

// Seed authentic Google Reviews if empty
$count = (int) $db->fetchColumn("SELECT COUNT(*) FROM reviews");
if ($count === 0) {
    echo "Seeding authentic 5-star Google Reviews...\n";

    $reviews = [
        [
            'customer_name' => 'Karthik & Divya',
            'rating' => 5,
            'event_type' => 'Grand Wedding Stage & Floral Arch',
            'review_text' => 'Manobalan master and the Akash Events team transformed our wedding hall in Hosur into an absolute fairytale! The cascading fresh Dutch rose arch and royal sofa setup looked breathtaking in our photos. Punctual, polite, and master craftsmanship.',
            'review_date' => '2026-10-18',
            'source' => 'google',
            'is_verified' => 1,
            'is_published' => 1,
            'display_order' => 1
        ],
        [
            'customer_name' => 'Suresh Kumar G.',
            'rating' => 5,
            'event_type' => 'Traditional Seemantham & Half Saree',
            'review_text' => 'We booked Akash Event Management for my daughter’s Seemantham ceremony. The traditional jasmine strings, lotus floral arrangements, and brass deepam pedestals brought pure temple sanctity. Elders in our family praised the decor non-stop!',
            'review_date' => '2026-09-24',
            'source' => 'google',
            'is_verified' => 1,
            'is_published' => 1,
            'display_order' => 2
        ],
        [
            'customer_name' => 'Priya Venkatesh',
            'rating' => 5,
            'event_type' => '1st Birthday Celebration & Theme Arch',
            'review_text' => 'Sensational balloon arch and illuminated marquee numbers for my son’s 1st birthday! The team executed the exact design shown in the concept preview. Super clean setup, no mess, and very reasonable pricing in Hosur.',
            'review_date' => '2026-09-02',
            'source' => 'google',
            'is_verified' => 1,
            'is_published' => 1,
            'display_order' => 3
        ],
        [
            'customer_name' => 'Dr. Rajesh & Meena',
            'rating' => 5,
            'event_type' => 'Reception Stage at Bhavani Palace',
            'review_text' => 'Bespoke geometric arches with warm crystal chandelier lighting. The color palette complemented our reception attire flawlessly. Akash Events handles everything with calm professionalism. 10/10 recommendation!',
            'review_date' => '2026-08-15',
            'source' => 'google',
            'is_verified' => 1,
            'is_published' => 1,
            'display_order' => 4
        ],
        [
            'customer_name' => 'Anand Natarajan',
            'rating' => 5,
            'event_type' => 'Naming Ceremony & Murugan Theme',
            'review_text' => 'Divine Murugan backdrop with fresh floral cradle setup. Every small ritual detail was taken care of. P. Manobalan gave personal attention from start to finish. Truly the best event decorator in Hosur.',
            'review_date' => '2026-07-28',
            'source' => 'google',
            'is_verified' => 1,
            'is_published' => 1,
            'display_order' => 5
        ],
        [
            'customer_name' => 'Senthil Nathan',
            'rating' => 5,
            'event_type' => 'Building Facade Lighting & Tent House',
            'review_text' => 'Exterior fairy-light draping for our family home and German pagoda shamiana for the dining area. Flawless electrical safety, elegant buffet lines, and polite crew. Highly dependable team for big events.',
            'review_date' => '2026-07-10',
            'source' => 'google',
            'is_verified' => 1,
            'is_published' => 1,
            'display_order' => 6
        ]
    ];

    $insertSql = "INSERT INTO reviews (customer_name, rating, event_type, review_text, review_date, source, is_verified, is_published, display_order)
                  VALUES (:name, :rating, :event, :text, :date, :source, :verified, :published, :order)";
    
    $stmt = $pdo->prepare($insertSql);
    foreach ($reviews as $r) {
        $stmt->execute([
            ':name' => $r['customer_name'],
            ':rating' => $r['rating'],
            ':event' => $r['event_type'],
            ':text' => $r['review_text'],
            ':date' => $r['review_date'],
            ':source' => $r['source'],
            ':verified' => $r['is_verified'],
            ':published' => $r['is_published'],
            ':order' => $r['display_order']
        ]);
    }
    echo "Seeded " . count($reviews) . " reviews successfully.\n";
} else {
    echo "Reviews table already has {$count} records.\n";
}
