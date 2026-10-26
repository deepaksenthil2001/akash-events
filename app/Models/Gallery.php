<?php
namespace App\Models;

use App\Core\Database;
use App\Helpers\Sanitizer;

class Gallery
{
    public static function getCategories(bool $activeOnly = true): array
    {
        $db = Database::getInstance();
        $sql = "SELECT c.*, (SELECT COUNT(*) FROM gallery_media m WHERE m.category_id = c.id AND m.is_published = 1) AS media_count 
                FROM gallery_categories c";
        if ($activeOnly) {
            $sql .= " WHERE c.is_active = 1";
        }
        $sql .= " ORDER BY c.display_order ASC, c.id ASC";
        return $db->fetchAll($sql);
    }

    public static function getMedia(
        ?int $categoryId = null, 
        ?string $categorySlug = null, 
        bool $publishedOnly = true,
        int $limit = 0,
        int $offset = 0
    ): array {
        $db = Database::getInstance();
        $sql = "SELECT m.*, c.name AS category_name, c.slug AS category_slug 
                FROM gallery_media m 
                LEFT JOIN gallery_categories c ON m.category_id = c.id 
                WHERE 1=1";
        $params = [];

        if ($publishedOnly) {
            $sql .= " AND m.is_published = 1";
        }

        if ($categoryId !== null) {
            $sql .= " AND m.category_id = :cat_id";
            $params[':cat_id'] = $categoryId;
        } elseif ($categorySlug !== null && $categorySlug !== 'all') {
            $sql .= " AND c.slug = :cat_slug";
            $params[':cat_slug'] = $categorySlug;
        }

        $sql .= " ORDER BY m.display_order ASC, m.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int) $limit;
            if ($offset > 0) {
                $sql .= " OFFSET " . (int) $offset;
            }
        }

        return $db->fetchAll($sql, $params);
    }

    public static function getFeatured(int $limit = 8): array
    {
        $db = Database::getInstance();
        $sql = "SELECT m.*, c.name AS category_name, c.slug AS category_slug 
                FROM gallery_media m 
                LEFT JOIN gallery_categories c ON m.category_id = c.id 
                WHERE m.is_published = 1 AND m.is_featured = 1 
                ORDER BY m.display_order ASC, m.id DESC 
                LIMIT {$limit}";
        return $db->fetchAll($sql);
    }

    public static function getById(int $id): ?array
    {
        $db = Database::getInstance();
        $sql = "SELECT m.*, c.name AS category_name, c.slug AS category_slug 
                FROM gallery_media m 
                LEFT JOIN gallery_categories c ON m.category_id = c.id 
                WHERE m.id = :id LIMIT 1";
        return $db->fetch($sql, [':id' => $id]);
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO gallery_media (
                    category_id, title, caption, media_type, file_path, 
                    mobile_file_path, thumbnail_path, is_featured, is_concept, 
                    display_order, is_published
                ) VALUES (
                    :cat_id, :title, :caption, :type, :file_path, 
                    :mobile_path, :thumb_path, :featured, :concept, 
                    :disp_order, :published
                )";

        $db->execute($sql, [
            ':cat_id' => !empty($data['category_id']) ? (int) $data['category_id'] : null,
            ':title' => $data['title'],
            ':caption' => $data['caption'] ?? null,
            ':type' => $data['media_type'] ?? 'image',
            ':file_path' => $data['file_path'],
            ':mobile_path' => $data['mobile_file_path'] ?? null,
            ':thumb_path' => $data['thumbnail_path'] ?? null,
            ':featured' => !empty($data['is_featured']) ? 1 : 0,
            ':concept' => !empty($data['is_concept']) ? 1 : 0,
            ':disp_order' => (int) ($data['display_order'] ?? 0),
            ':published' => !empty($data['is_published']) ? 1 : 0,
        ]);

        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $fields = [
            'category_id = :cat_id',
            'title = :title',
            'caption = :caption',
            'media_type = :type',
            'is_featured = :featured',
            'is_concept = :concept',
            'display_order = :disp_order',
            'is_published = :published',
        ];

        $params = [
            ':id' => $id,
            ':cat_id' => !empty($data['category_id']) ? (int) $data['category_id'] : null,
            ':title' => $data['title'],
            ':caption' => $data['caption'] ?? null,
            ':type' => $data['media_type'] ?? 'image',
            ':featured' => !empty($data['is_featured']) ? 1 : 0,
            ':concept' => !empty($data['is_concept']) ? 1 : 0,
            ':disp_order' => (int) ($data['display_order'] ?? 0),
            ':published' => !empty($data['is_published']) ? 1 : 0,
        ];

        if (!empty($data['file_path'])) {
            $fields[] = 'file_path = :file_path';
            $params[':file_path'] = $data['file_path'];
        }

        if (array_key_exists('mobile_file_path', $data)) {
            $fields[] = 'mobile_file_path = :mobile_path';
            $params[':mobile_path'] = $data['mobile_file_path'];
        }

        if (array_key_exists('thumbnail_path', $data)) {
            $fields[] = 'thumbnail_path = :thumb_path';
            $params[':thumb_path'] = $data['thumbnail_path'];
        }

        $sql = "UPDATE gallery_media SET " . implode(', ', $fields) . " WHERE id = :id";
        return $db->execute($sql, $params) > 0;
    }

    public static function togglePublish(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("UPDATE gallery_media SET is_published = 1 - is_published WHERE id = :id", [':id' => $id]) > 0;
    }

    public static function toggleFeatured(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("UPDATE gallery_media SET is_featured = 1 - is_featured WHERE id = :id", [':id' => $id]) > 0;
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("DELETE FROM gallery_media WHERE id = :id", [':id' => $id]) > 0;
    }

    public static function count(bool $publishedOnly = false, string $mediaType = ''): int
    {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM gallery_media WHERE 1=1";
        $params = [];

        if ($publishedOnly) {
            $sql .= " AND is_published = 1";
        }
        if (!empty($mediaType)) {
            $sql .= " AND media_type = :mtype";
            $params[':mtype'] = $mediaType;
        }

        return (int) $db->fetchColumn($sql, $params);
    }
}
