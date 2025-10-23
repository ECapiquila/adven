<?php

namespace App\Services;

use App\Models\HealthPost;

class HealthService
{
    public function __construct(
        private readonly HealthPost $healthPost,
        private readonly NotificationService $notifications,
        private readonly QueueService $queue,
        private readonly GamificationService $gamification,
    ) {
    }

    public function feed(): array
    {
        return $this->healthPost->latestApproved();
    }

    public function submit(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $postId = $this->healthPost->create($data);

        if (($data['status'] ?? 'pending') === 'pending') {
            $this->queue->dispatch('moderacao', 'HealthPostModeration', ['post_id' => $postId]);
        }

        $this->gamification->reward($data['author_id'], 8, 'saude_postagem', ['post_id' => $postId]);

        return $postId;
    }

    public function approve(int $postId, int $moderatorId): void
    {
        $this->healthPost->update($postId, ['status' => 'approved', 'updated_at' => date('Y-m-d H:i:s')]);
        $post = $this->healthPost->find($postId);
        if ($post) {
            $this->notifications->notifyUsers([$post['author_id']], 'saude.aprovado', ['post_id' => $postId]);
            $this->queue->dispatch('emails', 'HealthPostApproved', ['post_id' => $postId, 'recipient' => $post['author_id']]);
            $this->gamification->reward($moderatorId, 3, 'saude_moderacao', ['post_id' => $postId]);
        }
    }
}
