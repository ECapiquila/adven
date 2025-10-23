<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Http\Response;
use App\Core\Support\Container;
use App\Core\View\View;
use App\Services\ThemeService;

class HomeController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly ThemeService $themeService
    ) {
        parent::__construct($container, $view, $response);
    }

    public function landing(): string
    {
        $palette = $this->themeService->palette();
        return $this->renderLayout('Rede Social Adventista', 'home.landing', compact('palette'));
    }

    public function dashboard(): string
    {
        return $this->renderLayout('Feed Adventista', 'home.dashboard');
    }
}
