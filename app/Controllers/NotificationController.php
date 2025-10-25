<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\Http\Response;

final class NotificationController extends Controller
{
    public function index(Request $request): string
    {
        return $this->view('pages/notificacoes/index', [
            'title' => 'Notificações',
            'notifications' => [],
        ]);
    }

    public function unreadCount(Request $request): Response
    {
        return Response::json([
            'unread' => 0,
        ]);
    }

    public function latest(Request $request): Response
    {
        return Response::json([
            'data' => [],
        ]);
    }
}
