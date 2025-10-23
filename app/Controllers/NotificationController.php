<?php

namespace App\Controllers;

use App\Core\Auth\AuthManager;
use App\Core\Controller;
use App\Core\Http\Response;
use App\Core\Support\Container;
use App\Core\View\View;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly NotificationService $notifications,
        private readonly AuthManager $auth
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
        $items = $this->notifications->unread((int) $user['id']);
        return $this->renderLayout('Notificações', 'notifications.index', compact('items'));
    }
}
