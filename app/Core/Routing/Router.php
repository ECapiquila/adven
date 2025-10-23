<?php

namespace App\Core\Routing;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Support\Container;

class Router
{
    private array $routes = [];

    public function __construct(private readonly Container $container)
    {
    }

    public function add(string $method, string $path, callable|array $handler, ?string $name = null): void
    {
        $method = strtoupper($method);
        $path = '/' . trim($path, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        $this->routes[$method][$path] = [
            'handler' => $handler,
            'name' => $name,
        ];
    }

    public function get(string $path, callable|array $handler, ?string $name = null): void
    {
        $this->add('GET', $path, $handler, $name);
    }

    public function post(string $path, callable|array $handler, ?string $name = null): void
    {
        $this->add('POST', $path, $handler, $name);
    }

    public function match(Request $request): array
    {
        $method = $request->method();
        $path = $request->path();
        $routes = $this->routes[$method] ?? [];

        if (isset($routes[$path])) {
            return $routes[$path];
        }

        foreach ($routes as $route => $data) {
            $pattern = preg_replace('#\{([^}/]+)\}#', '(?P<$1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $data['params'] = $params;

                return $data;
            }
        }

        return ['handler' => [\App\Controllers\ErrorController::class, 'notFound'], 'params' => []];
    }

    public function dispatch(array $routeInfo, Request $request, Response $response)
    {
        $handler = $routeInfo['handler'];
        $params = $routeInfo['params'] ?? [];

        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = $this->container->make($class);
            return call_user_func_array([$controller, $method], array_values($params));
        }

        return $handler($request, $response, $params);
    }
}
