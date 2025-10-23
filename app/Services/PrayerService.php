<?php

namespace App\Services;

use App\Models\PrayerFollower;
use App\Models\PrayerReaction;
use App\Models\PrayerRequest;
use App\Models\PrayerResponse;

class PrayerService
{
    public function __construct(
        private readonly PrayerRequest $prayerRequest,
        private readonly PrayerResponse $responses,
        private readonly PrayerReaction $reactions,
        private readonly PrayerFollower $followers,
        private readonly NotificationService $notifications,
        private readonly RbacService $rbac,
        private readonly QueueService $queue,
        private readonly GamificationService $gamification,
    ) {
    }

    public function timeline(int $userId, int $scopeId): array
    {
        return $this->prayerRequest->recentForUserScope($userId, 'scope_id', $scopeId);
    }

    public function create(array $payload): int
    {
        $payload['created_at'] = date('Y-m-d H:i:s');
        $payload['updated_at'] = date('Y-m-d H:i:s');
        $prayerId = $this->prayerRequest->createRequest($payload);

        $leaders = $this->rbac->leadersForChurch($payload['scope_id'], ['Pastor', 'Ancião']);
        $leaderIds = array_values(array_unique(array_column($leaders, 'id')));
        $this->followers->create([
            'prayer_id' => $prayerId,
            'user_id' => $payload['author_id'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        foreach ($leaderIds as $leaderId) {
            if (!$leaderId) {
                continue;
            }
            $this->followers->create([
                'prayer_id' => $prayerId,
                'user_id' => $leaderId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($leaderIds) {
            $this->notifications->notifyUsers($leaderIds, 'prayer.created', [
                'prayer_id' => $prayerId,
                'title' => $payload['title'],
                'author_id' => $payload['author_id'],
            ]);
        }

        $this->queue->dispatch('emails', 'SendPrayerNotification', [
            'prayer_id' => $prayerId,
            'recipients' => $leaderIds,
        ]);

        $this->gamification->reward($payload['author_id'], 10, 'oracao_criada', ['prayer_id' => $prayerId]);

        return $prayerId;
    }

    public function react(int $prayerId, int $userId, string $type): void
    {
        $this->reactions->create([
            'prayer_id' => $prayerId,
            'user_id' => $userId,
            'type' => $type,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->gamification->reward($userId, 2, 'oracao_reacao', ['prayer_id' => $prayerId, 'tipo' => $type]);
    }

    public function respond(int $prayerId, int $userId, string $body): int
    {
        $responseId = $this->responses->create([
            'prayer_id' => $prayerId,
            'responder_id' => $userId,
            'body' => $body,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $followers = $this->followers->where('prayer_id', $prayerId);
        $recipientIds = array_column($followers, 'user_id');
        $this->notifications->notifyUsers($recipientIds, 'prayer.respondida', [
            'prayer_id' => $prayerId,
            'responder_id' => $userId,
        ]);
        $this->queue->dispatch('emails', 'SendPrayerResponse', [
            'prayer_id' => $prayerId,
            'responder_id' => $userId,
            'recipients' => $recipientIds,
        ]);
        $this->gamification->reward($userId, 5, 'oracao_resposta', ['prayer_id' => $prayerId]);

        return $responseId;
    }
}
