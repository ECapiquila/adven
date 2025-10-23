<?php

namespace App\Models;

use App\Core\Model;

class FamilyMessage extends Model
{
    protected string $table = 'family_messages';

    public function forThreadWithUsers(int $threadId): array
    {
        $sql = 'SELECT fm.*, u.name AS sender_name FROM family_messages fm
            JOIN users u ON u.id = fm.sender_id
            WHERE fm.thread_id = :thread ORDER BY fm.id ASC';
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['thread' => $threadId]);
        return $stmt->fetchAll();
    }
}
