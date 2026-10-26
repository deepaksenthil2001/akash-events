<?php
namespace App\Models;

use App\Core\Database;
use App\Helpers\Sanitizer;

class Service
{
    public static function getAll(bool $publishedOnly = false): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM services";
        $params = [];

        if ($publishedOnly) {
            $sql .= " WHERE is_published = 1";
        }

        $sql .= " ORDER BY display_order ASC, id ASC";
        return $db->fetchAll($sql, $params);
    }

    public static function getFeatured(int $limit = 6): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM services WHERE is_published = 1 AND is_featured = 1 ORDER BY display_order ASC LIMIT {$limit}";
        return $db->fetchAll($sql);
    }

    public static function getBySlug(string $slug): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM services WHERE slug = :slug LIMIT 1", [':slug' => $slug]);
    }

    public static function getById(int $id): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM services WHERE id = :id LIMIT 1", [':id' => $id]);
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $slug = !empty($data['slug']) ? Sanitizer::slug($data['slug']) : Sanitizer::slug($data['title']);

        // Ensure unique slug
        $existing = self::getBySlug($slug);
        if ($existing) {
            $slug .= '-' . time();
        }

        $sql = "INSERT INTO services (
                    slug, title, short_description, full_description, cover_image, 
                    banner_image, features_list, display_order, is_published, is_featured, 
                    meta_title, meta_description
                ) VALUES (
                    :slug, :title, :short_desc, :full_desc, :cover_img, 
                    :banner_img, :features, :disp_order, :published, :featured, 
                    :meta_title, :meta_desc
                )";

        $db->execute($sql, [
            ':slug' => $slug,
            ':title' => $data['title'],
            ':short_desc' => $data['short_description'],
            ':full_desc' => $data['full_description'] ?? '',
            ':cover_img' => $data['cover_image'],
            ':banner_img' => $data['banner_image'] ?? null,
            ':features' => $data['features_list'] ?? null,
            ':disp_order' => (int) ($data['display_order'] ?? 0),
            ':published' => !empty($data['is_published']) ? 1 : 0,
            ':featured' => !empty($data['is_featured']) ? 1 : 0,
            ':meta_title' => $data['meta_title'] ?? null,
            ':meta_desc' => $data['meta_description'] ?? null,
        ]);

        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $fields = [
            'title = :title',
            'short_description = :short_desc',
            'full_description = :full_desc',
            'features_list = :features',
            'display_order = :disp_order',
            'is_published = :published',
            'is_featured = :featured',
            'meta_title = :meta_title',
            'meta_description = :meta_desc',
        ];

        $params = [
            ':id' => $id,
            ':title' => $data['title'],
            ':short_desc' => $data['short_description'],
            ':full_desc' => $data['full_description'] ?? '',
            ':features' => $data['features_list'] ?? null,
            ':disp_order' => (int) ($data['display_order'] ?? 0),
            ':published' => !empty($data['is_published']) ? 1 : 0,
            ':featured' => !empty($data['is_featured']) ? 1 : 0,
            ':meta_title' => $data['meta_title'] ?? null,
            ':meta_desc' => $data['meta_description'] ?? null,
        ];

        if (!empty($data['slug'])) {
            $fields[] = 'slug = :slug';
            $params[':slug'] = Sanitizer::slug($data['slug']);
        }

        if (!empty($data['cover_image'])) {
            $fields[] = 'cover_image = :cover_img';
            $params[':cover_img'] = $data['cover_image'];
        }

        if (array_key_exists('banner_image', $data)) {
            $fields[] = 'banner_image = :banner_img';
            $params[':banner_img'] = $data['banner_image'];
        }

        $sql = "UPDATE services SET " . implode(', ', $fields) . " WHERE id = :id";
        return $db->execute($sql, $params) > 0;
    }

    public static function togglePublish(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("UPDATE services SET is_published = 1 - is_published WHERE id = :id", [':id' => $id]) > 0;
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("DELETE FROM services WHERE id = :id", [':id' => $id]) > 0;
    }

    public static function count(bool $publishedOnly = false): int
    {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM services";
        if ($publishedOnly) {
            $sql .= " WHERE is_published = 1";
        }
        return (int) $db->fetchColumn($sql);
    }
}
