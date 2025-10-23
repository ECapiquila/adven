<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Http\Response;
use App\Core\Support\Container;
use App\Core\View\View;
use App\Services\FeedService;
use App\Services\DevotionalService;
use App\Services\ComunicadoService;
use App\Services\EventService;

class FeedController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly FeedService $feedService,
        private readonly DevotionalService $devotionalService,
        private readonly ComunicadoService $comunicadoService,
        private readonly EventService $eventService
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $userId = $_SESSION['auth_user_id'] ?? 1;
        $feed = $this->feedService->getHomeFeed($userId);
        $devocional = $this->devotionalService->daily();
        $comunicados = $this->comunicadoService->slider();
        $eventos = $this->eventService->upcoming();

        return $this->renderLayout('Feed', 'feed.index', compact('feed', 'devocional', 'comunicados', 'eventos'));
    }
}
