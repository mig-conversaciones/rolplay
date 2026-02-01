<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

final class SystemSetting
{
    private const TABLE = 'system_settings';

    private static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('SELECT setting_value FROM ' . self::TABLE . ' WHERE setting_key = :key LIMIT 1');
            $stmt->execute(['key' => $key]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return $default;
            }
            $value = $row['setting_value'];
            self::$cache[$key] = $value;
            return $value;
        } catch (PDOException $e) {
            return $default;
        }
    }

    public static function getMany(array $keys): array
    {
        $result = [];
        $missing = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, self::$cache)) {
                $result[$key] = self::$cache[$key];
            } else {
                $missing[] = $key;
            }
        }

        if (empty($missing)) {
            return $result;
        }

        try {
            $db = Database::getConnection();
            $placeholders = implode(',', array_fill(0, count($missing), '?'));
            $stmt = $db->prepare('SELECT setting_key, setting_value FROM ' . self::TABLE . ' WHERE setting_key IN (' . $placeholders . ')');
            $stmt->execute($missing);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $result[$row['setting_key']] = $row['setting_value'];
                self::$cache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (PDOException $e) {
            return $result;
        }

        return $result;
    }

    public static function set(string $key, mixed $value): void
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('
                INSERT INTO ' . self::TABLE . ' (setting_key, setting_value, updated_at)
                VALUES (:key, :value, NOW())
                ON DUPLICATE KEY UPDATE
                    setting_value = VALUES(setting_value),
                    updated_at = NOW()
            ');
            $stmt->execute([
                'key' => $key,
                'value' => (string) $value,
            ]);
            self::$cache[$key] = (string) $value;
        } catch (PDOException $e) {
            return;
        }
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::set((string) $key, $value);
        }
    }
}
