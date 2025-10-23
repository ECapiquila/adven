<?php

namespace App\Controllers;

use App\Core\Auth\AuthManager;
use App\Core\Controller;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Security\Csrf;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Core\View\View;
use App\Services\ChatService;

class ChatController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly ChatService $chat,
        private readonly AuthManager $auth,
        private readonly Request $request,
        private readonly Validator $validator
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $user = $this->auth->user();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $threads = $this->chat->recentThreads((int) $user['id']);
        $requestedThread = (int) ($this->request->input('thread') ?? 0);
        $activeThreadId = $requestedThread ?: ($threads[0]['id'] ?? null);
        $messages = $activeThreadId ? $this->chat->fetchMessages((int) $activeThreadId) : [];
        return $this->renderLayout('Chat', 'chat.index', compact('threads', 'messages', 'activeThreadId'));
    }

    public function send(): void
    {
        $user = $this->auth->user();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $data = $this->request->all();
        if (!Csrf::validate($data['_token'] ?? null)) {
            header('Location: /chat');
            exit;
        }
        $errors = $this->validator->validate($data, [
            'thread_id' => 'required',
            'body' => 'required',
        ]);
        if ($errors) {
            header('Location: /chat');
            exit;
        }
        $this->chat->postMessage((int) $data['thread_id'], (int) $user['id'], $data['body']);
        header('Location: /chat?thread=' . (int) $data['thread_id']);
        exit;
    }

    public function poll(): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->response->json(['status' => 'unauthorized'], 401);
        }
        $threadId = (int) ($this->request->input('thread_id') ?? 0);
        $lastId = (int) ($this->request->input('after_id') ?? 0);
        $messages = $this->chat->fetchMessages($threadId, $lastId ?: null);
        return $this->response->json(['messages' => $messages]);
    }
}
