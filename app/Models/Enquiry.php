<?php
namespace App\Models;

use App\Core\Database;

class Enquiry
{
    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO enquiries (
                    customer_name, phone, email, event_type, 
                    preferred_date, event_location, guest_count, 
                    message, status, ip_address
                ) VALUES (
                    :name, :phone, :email, :event_type, 
                    :pref_date, :location, :guests, 
                    :message, 'new', :ip
                )";

        $db->execute($sql, [
            ':name' => $data['customer_name'],
            ':phone' => $data['phone'],
            ':email' => !empty($data['email']) ? $data['email'] : null,
            ':event_type' => $data['event_type'],
            ':pref_date' => !empty($data['preferred_date']) ? $data['preferred_date'] : null,
            ':location' => !empty($data['event_location']) ? $data['event_location'] : null,
            ':guests' => !empty($data['guest_count']) ? $data['guest_count'] : null,
            ':message' => $data['message'],
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        return (int) $db->lastInsertId();
    }

    public static function getAll(?string $status = null, int $limit = 0, int $offset = 0): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM enquiries WHERE 1=1";
        $params = [];

        if ($status !== null && $status !== 'all') {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int) $limit;
            if ($offset > 0) {
                $sql .= " OFFSET " . (int) $offset;
            }
        }

        return $db->fetchAll($sql, $params);
    }

    public static function getById(int $id): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM enquiries WHERE id = :id LIMIT 1", [':id' => $id]);
    }

    public static function updateStatus(int $id, string $status, ?string $adminNotes = null): bool
    {
        $db = Database::getInstance();
        $fields = ['status = :status'];
        $params = [':id' => $id, ':status' => $status];

        if ($adminNotes !== null) {
            $fields[] = 'admin_notes = :notes';
            $params[':notes'] = $adminNotes;
        }

        $sql = "UPDATE enquiries SET " . implode(', ', $fields) . " WHERE id = :id";
        return $db->execute($sql, $params) > 0;
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute("DELETE FROM enquiries WHERE id = :id", [':id' => $id]) > 0;
    }

    public static function count(?string $status = null): int
    {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM enquiries";
        $params = [];

        if ($status !== null && $status !== 'all') {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }

        return (int) $db->fetchColumn($sql, $params);
    }

    public static function getRecent(int $limit = 5): array
    {
        return self::getAll(null, $limit, 0);
    }

    public static function search(string $keyword): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM enquiries 
                WHERE customer_name LIKE :kw 
                   OR phone LIKE :kw 
                   OR email LIKE :kw 
                   OR event_type LIKE :kw 
                   OR event_location LIKE :kw 
                ORDER BY id DESC LIMIT 50";
        return $db->fetchAll($sql, [':kw' => "%{$keyword}%"]);
    }
}
