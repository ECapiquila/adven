<?php

namespace App\Models;

use App\Core\Model;

class ChatThread extends Model
{
    protected string $table = 'chat_threads';

    public function recentForUser(int $userId): array
    {
        $sql = 'SELECT t.* FROM chat_threads t JOIN chat_participants p ON p.thread_id = t.id WHERE p.user_id = :user ORDER BY t.id DESC LIMIT 20';
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['user' => $userId]);
        return $stmt->fetchAll();
    }
}
