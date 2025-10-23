<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\ChatThread;

class ChatService
{
    public function __construct(
        private readonly ChatThread $threads,
        private readonly ChatParticipant $participants,
        private readonly ChatMessage $messages,
    ) {
    }

    public function ensureDirectThread(array $userIds): int
    {
        sort($userIds);
        $hash = md5(implode('-', $userIds));
        $existing = $this->threads->firstWhere('subject', $hash);
        if ($existing) {
            return (int) $existing['id'];
        }

        $threadId = $this->threads->create([
            'subject' => $hash,
            'is_group' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        foreach ($userIds as $userId) {
            $this->participants->create([
                'thread_id' => $threadId,
                'user_id' => $userId,
                'joined_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return $threadId;
    }

    public function recentThreads(int $userId): array
    {
        return $this->threads->recentForUser($userId);
    }

    public function postMessage(int $threadId, int $senderId, string $body): int
    {
        return $this->messages->create([
            'thread_id' => $threadId,
            'sender_id' => $senderId,
            'body' => $body,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function fetchMessages(int $threadId, ?int $afterId = null): array
    {
        if ($afterId) {
            return $this->messages->afterMessage($threadId, $afterId);
        }

        return $this->messages->recentForThread($threadId, 50);
    }
}
