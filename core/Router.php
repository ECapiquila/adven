<?php

declare(strict_types=1);

namespace Core;

use Closure;
use Core\Http\Request;
use Core\Http\Response;

class Router
{
    /**
     * @var array<string, array<int, array{pattern:string, handler:mixed}>>
     */
    private array $routes = [];

    public function get(string $path, mixed $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, mixed $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function match(array $methods, string $path, mixed $handler): self
    {
        foreach ($methods as $method) {
            $this->add(strtoupper($method), $path, $handler);
        }

        return $this;
    }

    private function add(string $method, string $path, mixed $handler): self
    {
        $method = strtoupper($method);
        $pattern = $this->compilePattern($path);
        $this->routes[$method][] = ['pattern' => $pattern, 'handler' => $handler];

        return $this;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = '/' . trim($uri, '/');
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        $request = Request::fromGlobals();

        if ($method === 'POST') {
            csrf_verify($request);
        }

        foreach ($this->routes[$method] ?? [] as $route) {
            $matches = [];
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }

                $request->setRouteParams($params);
                $response = $this->invokeHandler($route['handler'], $request, $params);

                if ($response instanceof Response) {
                    $response->send();
                } elseif (is_string($response)) {
                    echo $response;
                }

                return;
            }
        }

        http_response_code(404);
        echo view('pages/404', ['title' => 'Página não encontrada']);
    }

    private function compilePattern(string $path): string
    {
        $path = '/' . trim($path, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P<$1>[^/]+)', $path);

        return '#^' . $pattern . '$#u';
    }

    private function invokeHandler(mixed $handler, Request $request, array $params): mixed
    {
        if ($handler instanceof Closure) {
            return $handler($request, $params);
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            if (is_string($class)) {
                $class = new $class();
            }

            if ($class instanceof Controller) {
                return $class->$method($request, ...array_values($params));
            }

            return $class->$method($request, ...array_values($params));
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $method] = explode('@', $handler, 2);
            $instance = new $class();

            return $instance->$method($request, ...array_values($params));
        }

        if (is_callable($handler)) {
            return $handler($request, ...array_values($params));
        }

        throw new \InvalidArgumentException('Invalid route handler provided.');
    }
}
