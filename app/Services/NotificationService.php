<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function __construct(private readonly Notification $notifications)
    {
    }

    public function notifyUsers(array $userIds, string $type, array $data): void
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        foreach ($userIds as $userId) {
            $this->notifications->create([
                'user_id' => $userId,
                'type' => $type,
                'data_json' => $payload,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function unread(int $userId): array
    {
        return $this->notifications->unreadForUser($userId);
    }

    public function markAsRead(int $notificationId): void
    {
        $this->notifications->markAsRead($notificationId);
    }
}
