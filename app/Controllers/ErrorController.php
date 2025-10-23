<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Http\Response;
use App\Core\Support\Container;
use App\Core\View\View;

class ErrorController extends Controller
{
    public function __construct(Container $container, View $view, Response $response)
    {
        parent::__construct($container, $view, $response);
    }

    public function notFound(): string
    {
        $this->response->status(404);
        return $this->renderLayout('Página não encontrada', 'errors.404');
    }
}
