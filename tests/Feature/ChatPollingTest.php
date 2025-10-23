<?php

use App\Models\User;
use App\Services\ChatService;

require_once __DIR__ . '/../TestCase.php';

class ChatPollingTest extends TestCase
{
    public function testPollingReturnsNewMessages(): void
    {
        /** @var User $users */
        $users = $this->container->make(User::class);
        $userA = $users->createFromRegistration([
            'name' => 'Utilizador A',
            'email' => 'a@example.com',
            'phone' => '+244900000444',
            'password' => password_hash('Senha123', PASSWORD_BCRYPT),
        ]);
        $userB = $users->createFromRegistration([
            'name' => 'Utilizador B',
            'email' => 'b@example.com',
            'phone' => '+244900000555',
            'password' => password_hash('Senha123', PASSWORD_BCRYPT),
        ]);

        /** @var ChatService $chat */
        $chat = $this->container->make(ChatService::class);
        $threadId = $chat->ensureDirectThread([$userA, $userB]);
        $chat->postMessage($threadId, $userA, 'Primeira mensagem');
        $messages = $chat->fetchMessages($threadId);
        $this->assertCount(1, $messages);

        $chat->postMessage($threadId, $userB, 'Resposta');
        $newMessages = $chat->fetchMessages($threadId, (int) $messages[0]['id']);
        $this->assertCount(1, $newMessages);
        $this->assertEquals('Resposta', $newMessages[0]['body']);
    }
}
