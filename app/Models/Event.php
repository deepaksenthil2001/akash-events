<?php
namespace App\Models;

use App\Core\Database;
use App\Helpers\Sanitizer;

class Event
{
    /**
     * Get all events with photo count and category info
     */
    public static function getAll(
        ?int $categoryId = null, 
        ?string $categorySlug = null, 
        bool $publishedOnly = true,
        int $limit = 0,
        int $offset = 0
    ): array {
        $db = Database::getInstance();
        $sql = "SELECT e.*, c.name AS category_name, c.slug AS category_slug,
                       (SELECT COUNT(*) FROM gallery_media m WHERE m.event_id = e.id " . ($publishedOnly ? "AND m.is_published = 1" : "") . ") AS photo_count
                FROM gallery_events e
                LEFT JOIN gallery_categories c ON e.category_id = c.id
                WHERE 1=1";
        $params = [];

        if ($publishedOnly) {
            $sql .= " AND e.is_published = 1";
        }

        if ($categoryId !== null && $categoryId > 0) {
            $sql .= " AND e.category_id = :cat_id";
            $params[':cat_id'] = $categoryId;
        } elseif ($categorySlug !== null && $categorySlug !== 'all' && $categorySlug !== '') {
            $sql .= " AND c.slug = :cat_slug";
            $params[':cat_slug'] = $categorySlug;
        }

        $sql .= " ORDER BY e.display_order ASC, e.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset > 0) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }

        return $db->fetchAll($sql, $params);
    }

    /**
     * Get featured events for homepage or showcase
     */
    public static function getFeatured(int $limit = 6): array
    {
        $db = Database::getInstance();
        $sql = "SELECT e.*, c.name AS category_name, c.slug AS category_slug,
                       (SELECT COUNT(*) FROM gallery_media m WHERE m.event_id = e.id AND m.is_published = 1) AS photo_count
                FROM gallery_events e
                LEFT JOIN gallery_categories c ON e.category_id = c.id
                WHERE e.is_published = 1 AND e.is_featured = 1
                ORDER BY e.display_order ASC, e.id DESC
                LIMIT {$limit}";
        return $db->fetchAll($sql);
    }

    /**
     * Get single event by ID
     */
    public static function getById(int $id): ?array
    {
        $db = Database::getInstance();
        $sql = "SELECT e.*, c.name AS category_name, c.slug AS category_slug,
                       (SELECT COUNT(*) FROM gallery_media m WHERE m.event_id = e.id) AS photo_count
                FROM gallery_events e
                LEFT JOIN gallery_categories c ON e.category_id = c.id
                WHERE e.id = :id LIMIT 1";
        return $db->fetch($sql, [':id' => $id]);
    }

    /**
     * Get single event by slug
     */
    public static function getBySlug(string $slug): ?array
    {
        $db = Database::getInstance();
        $sql = "SELECT e.*, c.name AS category_name, c.slug AS category_slug,
                       (SELECT COUNT(*) FROM gallery_media m WHERE m.event_id = e.id AND m.is_published = 1) AS photo_count
                FROM gallery_events e
                LEFT JOIN gallery_categories c ON e.category_id = c.id
                WHERE e.slug = :slug LIMIT 1";
        return $db->fetch($sql, [':slug' => $slug]);
    }

    /**
     * Get all photographs belonging to an event
     */
    public static function getPhotos(int $eventId, bool $publishedOnly = true): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM gallery_media WHERE event_id = :event_id";
        if ($publishedOnly) {
            $sql .= " AND is_published = 1";
        }
        $sql .= " ORDER BY display_order ASC, id ASC";
        return $db->fetchAll($sql, [':event_id' => $eventId]);
    }

    /**
     * Create a new event
     */
    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $slug = !empty($data['slug']) ? Sanitizer::slug($data['slug']) : Sanitizer::slug($data['title']);

        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while ($db->fetch("SELECT id FROM gallery_events WHERE slug = :s", [':s' => $slug])) {
            $slug = $baseSlug . '-' . (++$counter);
        }

        $sql = "INSERT INTO gallery_events (
            category_id, title, slug, event_type, event_date, location, 
            description, cover_image, is_featured, is_published, display_order
        ) VALUES (
            :cat_id, :title, :slug, :etype, :edate, :loc, 
            :desc, :cover, :feat, :published, :disp
        )";

        $db->execute($sql, [
            ':cat_id' => !empty($data['category_id']) ? (int)$data['category_id'] : null,
            ':title' => $data['title'],
            ':slug' => $slug,
            ':etype' => $data['event_type'] ?? null,
            ':edate' => !empty($data['event_date']) ? $data['event_date'] : null,
            ':loc' => $data['location'] ?? null,
            ':desc' => $data['description'] ?? null,
            ':cover' => $data['cover_image'] ?? null,
            ':feat' => !empty($data['is_featured']) ? 1 : 0,
            ':published' => !empty($data['is_published']) ? 1 : 0,
            ':disp' => (int)($data['display_order'] ?? 0),
        ]);

        return (int)$db->lastInsertId();
    }

    /**
     * Update existing event
     */
    public static function update(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $fields = [
            'category_id = :cat_id',
            'title = :title',
            'event_type = :etype',
            'event_date = :edate',
            'location = :loc',
            'description = :desc',
            'is_featured = :feat',
            'is_published = :published',
            'display_order = :disp',
        ];

        $params = [
            ':id' => $id,
            ':cat_id' => !empty($data['category_id']) ? (int)$data['category_id'] : null,
            ':title' => $data['title'],
            ':etype' => $data['event_type'] ?? null,
            ':edate' => !empty($data['event_date']) ? $data['event_date'] : null,
            ':loc' => $data['location'] ?? null,
            ':desc' => $data['description'] ?? null,
            ':feat' => !empty($data['is_featured']) ? 1 : 0,
            ':published' => !empty($data['is_published']) ? 1 : 0,
            ':disp' => (int)($data['display_order'] ?? 0),
        ];

        if (!empty($data['slug'])) {
            $fields[] = 'slug = :slug';
            $params[':slug'] = Sanitizer::slug($data['slug']);
        }

        if (!empty($data['cover_image'])) {
            $fields[] = 'cover_image = :cover';
            $params[':cover'] = $data['cover_image'];
        }

        $sql = "UPDATE gallery_events SET " . implode(', ', $fields) . " WHERE id = :id";
        return $db->execute($sql, $params) > 0;
    }

    /**
     * Delete event and all its associated photos
     */
    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        // Delete all photo files from disk first
        $photos = self::getPhotos($id, false);
        foreach ($photos as $p) {
            \App\Helpers\Uploader::deleteFile($p['file_path']);
            if (!empty($p['thumbnail_path'])) {
                \App\Helpers\Uploader::deleteFile($p['thumbnail_path']);
            }
        }
        $db->execute("DELETE FROM gallery_media WHERE event_id = :id", [':id' => $id]);
        return $db->execute("DELETE FROM gallery_events WHERE id = :id", [':id' => $id]) > 0;
    }

    /**
     * Toggle publication status
     */
    public static function togglePublish(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("UPDATE gallery_events SET is_published = 1 - is_published WHERE id = :id", [':id' => $id]) > 0;
    }

    /**
     * Toggle featured status
     */
    public static function toggleFeatured(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("UPDATE gallery_events SET is_featured = 1 - is_featured WHERE id = :id", [':id' => $id]) > 0;
    }

    /**
     * Add single photo to an event
     */
    public static function addPhoto(int $eventId, array $photoData): int
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO gallery_media (
            category_id, event_id, title, caption, media_type, file_path, 
            thumbnail_path, is_featured, is_concept, display_order, is_published
        ) VALUES (
            :cat_id, :event_id, :title, :caption, :type, :file_path, 
            :thumb, :feat, 0, :disp, 1
        )";

        $db->execute($sql, [
            ':cat_id' => !empty($photoData['category_id']) ? (int)$photoData['category_id'] : null,
            ':event_id' => $eventId,
            ':title' => $photoData['title'] ?? 'Event Photograph',
            ':caption' => $photoData['caption'] ?? null,
            ':type' => $photoData['media_type'] ?? 'image',
            ':file_path' => $photoData['file_path'],
            ':thumb' => $photoData['thumbnail_path'] ?? null,
            ':feat' => !empty($photoData['is_featured']) ? 1 : 0,
            ':disp' => (int)($photoData['display_order'] ?? 0),
        ]);

        $photoId = (int)$db->lastInsertId();

        // If event doesn't have a cover image, set this as cover
        $event = self::getById($eventId);
        if ($event && empty($event['cover_image'])) {
            $db->execute("UPDATE gallery_events SET cover_image = :img WHERE id = :id", [
                ':img' => $photoData['file_path'],
                ':id' => $eventId
            ]);
        }

        return $photoId;
    }

    /**
     * Delete individual photo from an event
     */
    public static function deletePhoto(int $photoId): bool
    {
        $db = Database::getInstance();
        $photo = $db->fetch("SELECT * FROM gallery_media WHERE id = :id", [':id' => $photoId]);
        if ($photo) {
            \App\Helpers\Uploader::deleteFile($photo['file_path']);
            if (!empty($photo['thumbnail_path'])) {
                \App\Helpers\Uploader::deleteFile($photo['thumbnail_path']);
            }
            return $db->execute("DELETE FROM gallery_media WHERE id = :id", [':id' => $photoId]) > 0;
        }
        return false;
    }

    /**
     * Set a photo as event cover
     */
    public static function setCover(int $eventId, string $filePath): bool
    {
        $db = Database::getInstance();
        return $db->execute("UPDATE gallery_events SET cover_image = :img WHERE id = :id", [
            ':img' => $filePath,
            ':id' => $eventId
        ]) > 0;
    }

    /**
     * Total count of events
     */
    public static function count(bool $publishedOnly = false): int
    {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM gallery_events";
        if ($publishedOnly) {
            $sql .= " WHERE is_published = 1";
        }
        return (int)$db->fetchColumn($sql);
    }
}

