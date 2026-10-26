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

    public static function set(string $key, string $value): bool
    {
        $db = Database::getInstance();
        $sql = "UPDATE site_settings SET setting_value = :val WHERE setting_key = :key";
        return $db->execute($sql, [':val' => $value, ':key' => $key]) > 0;
    }

    public static function updateBatch(array $keyValues): bool
    {
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $stmt = $db->getPdo()->prepare("UPDATE site_settings SET setting_value = :val WHERE setting_key = :key");
            foreach ($keyValues as $key => $val) {
                $stmt->execute([':val' => (string) $val, ':key' => (string) $key]);
            }
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
