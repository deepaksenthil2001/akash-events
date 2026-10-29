<?php
namespace App\Models;

use App\Core\Database;

class Setting
{
    public static function get(string $key, string $default = ''): string
    {
        $db = Database::getInstance();
        $val = $db->fetchColumn("SELECT setting_value FROM site_settings WHERE setting_key = :k LIMIT 1", [':k' => $key]);
        return $val !== false && $val !== null ? (string) $val : $default;
    }

    public static function getByGroup(string $group): array
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll("SELECT * FROM site_settings WHERE setting_group = :grp ORDER BY id ASC", [':grp' => $group]);
        $map = [];
        foreach ($rows as $r) {
            $map[$r['setting_key']] = $r;
        }
        return $map;
    }

    public static function getAll(): array
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll("SELECT * FROM site_settings ORDER BY setting_group ASC, id ASC");
        $map = [];
        foreach ($rows as $r) {
            $map[$r['setting_key']] = $r['setting_value'];
        }
        return $map;
    }

    public static function set(string $key, string $value, string $group = 'general'): bool
    {
        $db = Database::getInstance();
        $exists = $db->fetchColumn("SELECT COUNT(*) FROM site_settings WHERE setting_key = :k", [':k' => $key]);
        if ((int)$exists > 0) {
            $sql = "UPDATE site_settings SET setting_value = :val WHERE setting_key = :key";
            return $db->execute($sql, [':val' => $value, ':key' => $key]) !== false;
        } else {
            $sql = "INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES (:key, :val, :grp)";
            return $db->execute($sql, [':key' => $key, ':val' => $value, ':grp' => $group]) !== false;
        }
    }

    public static function updateBatch(array $keyValues, string $defaultGroup = 'general'): bool
    {
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $checkStmt = $db->getPdo()->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = :key");
            $updateStmt = $db->getPdo()->prepare("UPDATE site_settings SET setting_value = :val WHERE setting_key = :key");
            $insertStmt = $db->getPdo()->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES (:key, :val, :grp)");

            foreach ($keyValues as $key => $val) {
                $checkStmt->execute([':key' => (string) $key]);
                if ((int)$checkStmt->fetchColumn() > 0) {
                    $updateStmt->execute([':val' => (string) $val, ':key' => (string) $key]);
                } else {
                    $insertStmt->execute([':key' => (string) $key, ':val' => (string) $val, ':grp' => $defaultGroup]);
                }
            }
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
