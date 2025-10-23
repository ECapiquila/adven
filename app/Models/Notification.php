<?php

namespace App\Models;

use App\Core\Model;

class Notification extends Model
{
    protected string $table = 'notifications';

    public function unreadForUser(int $userId): array
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM notifications WHERE user_id = :user AND read_at IS NULL ORDER BY created_at DESC LIMIT 20');
        $stmt->execute(['user' => $userId]);
        return $stmt->fetchAll();
    }

    public function markAsRead(int $id): void
    {
        $stmt = $this->pdo()->prepare('UPDATE notifications SET read_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
