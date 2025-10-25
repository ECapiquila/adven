<?php

declare(strict_types=1);

use Core\Autoloader;
use Core\Router;
use Core\Session;

$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require $vendorAutoload;
}

require __DIR__ . '/../core/Autoloader.php';
require __DIR__ . '/../core/helpers.php';
require __DIR__ . '/../helpers/csrf.php';

Autoloader::register([
    'App\\' => __DIR__ . '/../app',
    'Core\\' => __DIR__ . '/../core',
]);

$session = Session::instance();

$timezone = config('app.timezone', 'Africa/Luanda');
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set($timezone);
}

$router = new Router();

require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
