<?php

namespace App\Services;

class FeedService
{
    public function getHomeFeed(int $userId): array
    {
        return [
            'comunicados' => [],
            'stories' => [],
            'devocional' => [],
            'posts' => [],
        ];
    }
}
