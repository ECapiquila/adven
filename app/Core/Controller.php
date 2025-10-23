<?php

namespace App\Core;

use App\Core\Http\Response;
use App\Core\Support\Config;
use App\Core\Support\Container;
use App\Core\View\View;

abstract class Controller
{
    public function __construct(
        protected readonly Container $container,
        protected readonly View $view,
        protected readonly Response $response
    ) {
    }

    protected function render(string $template, array $data = []): string
    {
        return $this->view->render($template, $data);
    }

    protected function renderLayout(string $pageTitle, string $template, array $data = []): string
    {
        $content = $this->render($template, $data);
        $appName = Config::get('app.name', 'Rede Adventista');
        $title = $pageTitle;
        ob_start();
        include __DIR__ . '/../../resources/views/layouts/base.php';
        return ob_get_clean();
    }
}
