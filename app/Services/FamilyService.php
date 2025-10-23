<?php

namespace App\Services;

use App\Models\FamilyMessage;
use App\Models\FamilyStaff;
use App\Models\FamilyThread;

class FamilyService
{
    public function __construct(
        private readonly FamilyThread $familyThread,
        private readonly FamilyMessage $familyMessage,
        private readonly FamilyStaff $familyStaff,
        private readonly NotificationService $notifications,
        private readonly QueueService $queue,
        private readonly GamificationService $gamification,
        private readonly RbacService $rbac,
    ) {
    }

    public function conversations(int $churchId): array
    {
        return $this->familyThread->recentForChurch($churchId);
    }

    public function messages(int $threadId): array
    {
        return $this->familyMessage->forThreadWithUsers($threadId);
    }

    public function createThread(array $payload): int
    {
        $payload['created_at'] = date('Y-m-d H:i:s');
        $payload['updated_at'] = date('Y-m-d H:i:s');
        $threadId = $this->familyThread->create($payload);

        $staff = $this->familyStaff->where('church_id', $payload['church_id']);
        $staffIds = array_column($staff, 'user_id');
        $this->notifications->notifyUsers($staffIds, 'familia.thread_aberta', [
            'thread_id' => $threadId,
            'author_id' => $payload['author_id'],
        ]);
        $this->queue->dispatch('emails', 'FamilyThreadOpened', [
            'thread_id' => $threadId,
            'recipients' => $staffIds,
        ]);
        $this->gamification->reward($payload['author_id'], 6, 'familia_thread', ['thread_id' => $threadId]);

        return $threadId;
    }

    public function postMessage(int $threadId, int $senderId, string $body, bool $isStaffNote = false): int
    {
        $messageId = $this->familyMessage->create([
            'thread_id' => $threadId,
            'sender_id' => $senderId,
            'body' => $body,
            'is_staff_note' => $isStaffNote ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $thread = $this->familyThread->find($threadId);
        if ($thread) {
            $participants = $this->familyStaff->where('church_id', $thread['church_id']);
            $participantIds = array_column($participants, 'user_id');
            $participantIds[] = $thread['author_id'];
            $participantIds = array_values(array_unique($participantIds));
            $this->notifications->notifyUsers($participantIds, 'familia.nova_mensagem', [
                'thread_id' => $threadId,
                'sender_id' => $senderId,
            ]);
            $this->queue->dispatch('emails', 'FamilyThreadMensagem', [
                'thread_id' => $threadId,
                'sender_id' => $senderId,
                'recipients' => $participantIds,
            ]);
        }

        $this->gamification->reward($senderId, 3, 'familia_mensagem', ['thread_id' => $threadId]);

        return $messageId;
    }
}
