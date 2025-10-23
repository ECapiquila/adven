<?php

namespace App\Services;

use App\Models\QueueJob;

class QueueService
{
    public function __construct(private readonly QueueJob $jobs)
    {
    }

    public function dispatch(string $queue, string $type, array $payload, int $delaySeconds = 0): int
    {
        $availableAt = date('Y-m-d H:i:s', time() + $delaySeconds);
        return $this->jobs->enqueue([
            'queue' => $queue,
            'job_type' => $type,
            'payload_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'attempts' => 0,
            'available_at' => $availableAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function reserve(string $queue): ?array
    {
        return $this->jobs->reserveNext($queue);
    }

    public function complete(int $jobId): void
    {
        $this->jobs->markCompleted($jobId);
    }
}
