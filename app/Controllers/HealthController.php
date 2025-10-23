<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Http\Response;
use App\Core\Support\Container;
use App\Core\View\View;
use App\Services\HealthService;

class HealthController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly HealthService $healthService
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $posts = $this->healthService->feed();
        return $this->renderLayout('Saúde & Bem-Estar', 'health.index', compact('posts'));
    }

    public function createForm(): string
    {
        return $this->renderLayout('Partilhar Dica de Saúde', 'health.create');
    }
}
