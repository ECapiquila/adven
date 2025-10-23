<?php

use App\Core\Application;
use App\Core\Autoloader;
use App\Core\Auth\AuthManager;
use App\Core\Database\Connection;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;
use App\Core\Support\Config;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Core\View\View;
use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';

$autoloader = new Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/../app');
$autoloader->addNamespace('Database', __DIR__ . '/../database');
$autoloader->register();

Config::load(__DIR__ . '/../config');

$container = new Container();
$container->singleton(Request::class, fn() => new Request());
$container->singleton(Response::class, fn() => new Response());
$container->singleton(View::class, fn() => new View(__DIR__ . '/../resources/views'));
$container->singleton(Connection::class, fn() => new Connection());
$container->singleton(Validator::class, fn() => new Validator());
$container->singleton(User::class, fn($c) => new User($c->make(Connection::class)));
$container->singleton(AuthManager::class, fn($c) => new AuthManager($c->make(User::class)));

$router = new Router($container);

require __DIR__ . '/../routes/web.php';

$app = new Application($router, $container->make(Request::class), $container->make(Response::class), $container);
$app->run();
