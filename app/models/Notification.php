<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Notification extends Model
{
    public function listByUser(int $userId, int $limit = 50): array
    {
        $limit = max(1, min(200, $limit));
        return $this->fetchAll(
            'SELECT id, type, title, message, is_read, link, created_at
             FROM notifications
             WHERE user_id = :user_id
             ORDER BY created_at DESC
             LIMIT ' . $limit,
            [
                'user_id' => $userId,
            ]
        );
    }

    public function listUnreadByUser(int $userId, int $limit = 50): array
    {
        $limit = max(1, min(200, $limit));
        return $this->fetchAll(
            'SELECT id, type, title, message, is_read, link, created_at
             FROM notifications
             WHERE user_id = :user_id AND is_read = 0
             ORDER BY created_at DESC
             LIMIT ' . $limit,
            [
                'user_id' => $userId,
            ]
        );
    }

    public function create(int $userId, string $type, string $title, string $message, ?string $link = null): void
    {
        $this->query(
            'INSERT INTO notifications (user_id, type, title, message, link)
             VALUES (:user_id, :type, :title, :message, :link)',
            [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
            ]
        );
    }

    public function countUnreadByUser(int $userId): int
    {
        $row = $this->fetchOne(
            'SELECT COUNT(*) AS total
             FROM notifications
             WHERE user_id = :user_id AND is_read = 0',
            ['user_id' => $userId]
        );

        return (int) ($row['total'] ?? 0);
    }

    public function markRead(int $id, int $userId): void
    {
        $this->query(
            'UPDATE notifications
             SET is_read = 1
             WHERE id = :id AND user_id = :user_id',
            [
                'id' => $id,
                'user_id' => $userId,
            ]
        );
    }

    public function markAllRead(int $userId): void
    {
        $this->query(
            'UPDATE notifications
             SET is_read = 1
             WHERE user_id = :user_id AND is_read = 0',
            ['user_id' => $userId]
        );
    }
}
