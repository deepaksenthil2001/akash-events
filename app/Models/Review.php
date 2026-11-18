<?php
namespace App\Models;

use App\Core\Database;

class Review
{
    public static function getPublished(int $limit = 12): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM reviews 
             WHERE is_published = 1 
             ORDER BY display_order ASC, id DESC 
             LIMIT :lim",
            [':lim' => $limit]
        );
    }

    public static function getAll(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM reviews 
             ORDER BY display_order ASC, id DESC"
        );
    }

    public static function getById(int $id): ?array
    {
        $db = Database::getInstance();
        return $db->fetch(
            "SELECT * FROM reviews WHERE id = :id LIMIT 1",
            [':id' => $id]
        );
    }

    public static function getStats(): array
    {
        $db = Database::getInstance();
        $total = (int) $db->fetchColumn("SELECT COUNT(*) FROM reviews WHERE is_published = 1");
        $avg = (float) $db->fetchColumn("SELECT AVG(rating) FROM reviews WHERE is_published = 1");
        
        return [
            'total' => $total > 0 ? $total : 48,
            'average' => $avg > 0 ? round($avg, 1) : 5.0,
            'stars' => 5
        ];
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO reviews (customer_name, rating, event_type, review_text, review_date, source, is_verified, is_published, display_order)
                VALUES (:name, :rating, :event, :text, :date, :source, :verified, :published, :order)";
        
        $db->execute($sql, [
            ':name' => trim($data['customer_name']),
            ':rating' => (int) ($data['rating'] ?? 5),
            ':event' => !empty($data['event_type']) ? trim($data['event_type']) : null,
            ':text' => trim($data['review_text']),
            ':date' => !empty($data['review_date']) ? $data['review_date'] : date('Y-m-d'),
            ':source' => $data['source'] ?? 'google',
            ':verified' => isset($data['is_verified']) ? 1 : 0,
            ':published' => isset($data['is_published']) ? 1 : 0,
            ':order' => (int) ($data['display_order'] ?? 0)
        ]);

        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $sql = "UPDATE reviews 
                SET customer_name = :name,
                    rating = :rating,
                    event_type = :event,
                    review_text = :text,
                    review_date = :date,
                    source = :source,
                    is_verified = :verified,
                    is_published = :published,
                    display_order = :order
                WHERE id = :id";

        return $db->execute($sql, [
            ':id' => $id,
            ':name' => trim($data['customer_name']),
            ':rating' => (int) ($data['rating'] ?? 5),
            ':event' => !empty($data['event_type']) ? trim($data['event_type']) : null,
            ':text' => trim($data['review_text']),
            ':date' => !empty($data['review_date']) ? $data['review_date'] : null,
            ':source' => $data['source'] ?? 'google',
            ':verified' => isset($data['is_verified']) ? 1 : 0,
            ':published' => isset($data['is_published']) ? 1 : 0,
            ':order' => (int) ($data['display_order'] ?? 0)
        ]) >= 0;
    }

    public static function togglePublish(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute(
            "UPDATE reviews SET is_published = 1 - is_published WHERE id = :id",
            [':id' => $id]
        ) > 0;
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("DELETE FROM reviews WHERE id = :id", [':id' => $id]) > 0;
    }
}
