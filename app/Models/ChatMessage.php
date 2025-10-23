<?php

namespace App\Models;

use App\Core\Model;

class ChatMessage extends Model
{
    protected string $table = 'chat_messages';

    public function recentForThread(int $threadId, int $limit = 50): array
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM chat_messages WHERE thread_id = :thread ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':thread', $threadId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $messages = array_reverse($stmt->fetchAll());
        return $messages;
    }

    public function afterMessage(int $threadId, int $afterId): array
    {
        $sql = 'SELECT * FROM chat_messages WHERE thread_id = :thread AND id > :after ORDER BY id ASC';
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['thread' => $threadId, 'after' => $afterId]);
        return $stmt->fetchAll();
    }
}
