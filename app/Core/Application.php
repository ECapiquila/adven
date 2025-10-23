<?php

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;
use App\Core\Support\Config;
use App\Core\Support\Container;

class Application
{
    public function __construct(
        private readonly Router $router,
        private readonly Request $request,
        private readonly Response $response,
        private readonly Container $container,
    ) {
    }

    public function run(): void
    {
        $this->bootstrap();
        $routeInfo = $this->router->match($this->request);
        $result = $this->router->dispatch($routeInfo, $this->request, $this->response);

        if ($result instanceof Response) {
            $result->send();
            return;
        }

        if (is_string($result)) {
            $this->response->setContent($result)->send();
            return;
        }
    }

    private function bootstrap(): void
    {
        date_default_timezone_set(Config::get('app.timezone', 'UTC'));
        setlocale(LC_TIME, Config::get('app.locale', 'en_US.UTF-8'));
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['locale'])) {
            $_SESSION['locale'] = Config::get('app.locale', 'pt_AO');
        }
    }
}
