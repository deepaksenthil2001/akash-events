<?php
namespace App\Models;

use App\Core\Database;

class Admin
{
    public static function getById(int $id): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT id, username, email, full_name, role, last_login, created_at FROM admins WHERE id = :id LIMIT 1", [':id' => $id]);
    }

    public static function getByUsername(string $username): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM admins WHERE username = :u LIMIT 1", [':u' => $username]);
    }

    public static function findByUsername(string $username): ?array
    {
        return self::getByUsername($username);
    }

    public static function getAll(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT id, username, email, full_name, role, last_login, created_at FROM admins ORDER BY id ASC");
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO admins (username, email, password_hash, full_name, role) VALUES (:u, :e, :h, :f, :r)";
        $db->execute($sql, [
            ':u' => $data['username'],
            ':e' => $data['email'],
            ':h' => $hash,
            ':f' => $data['full_name'],
            ':r' => $data['role'] ?? 'admin'
        ]);
        return (int) $db->lastInsertId();
    }

    public static function updateProfile(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $sql = "UPDATE admins SET email = :e, full_name = :f WHERE id = :id";
        return $db->execute($sql, [
            ':id' => $id,
            ':e' => $data['email'],
            ':f' => $data['full_name']
        ]) > 0;
    }

    public static function updatePassword(int $id, string $plainPassword): bool
    {
        $db = Database::getInstance();
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE admins SET password_hash = :h WHERE id = :id";
        return $db->execute($sql, [':id' => $id, ':h' => $hash]) > 0;
    }

    public static function count(): int
    {
        $db = Database::getInstance();
        return (int) $db->fetchColumn("SELECT COUNT(*) FROM admins");
    }

    public static function existsByUsername(string $username, ?int $excludeId = null): bool
    {
        $db = Database::getInstance();
        if ($excludeId !== null) {
            $cnt = $db->fetchColumn("SELECT COUNT(*) FROM admins WHERE username = :u AND id != :id", [':u' => $username, ':id' => $excludeId]);
        } else {
            $cnt = $db->fetchColumn("SELECT COUNT(*) FROM admins WHERE username = :u", [':u' => $username]);
        }
        return (int) $cnt > 0;
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM admins WHERE id = :id";
        return $db->execute($sql, [':id' => $id]) > 0;
    }
}
