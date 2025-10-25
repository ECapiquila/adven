<?php

declare(strict_types=1);

use Core\Session;
use App\Support\ThemeManager;

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $base = dirname(__DIR__);

        return $path ? $base . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $base;
    }
}

if (!function_exists('config_path')) {
    function config_path(string $path = ''): string
    {
        return base_path('config' . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : ''));
    }
}

if (!function_exists('config')) {
    /**
     * Retrieve values from config files located in config/*.php.
     *
     * @param string $key Example: app.name or database.connections.mysql.host
     * @param mixed $default
     */
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        [$file, $nested] = array_pad(explode('.', $key, 2), 2, null);
        if ($file === null) {
            return $default;
        }

        if (!array_key_exists($file, $cache)) {
            $filePath = config_path($file . '.php');
            $cache[$file] = is_file($filePath) ? require $filePath : [];
        }

        if ($nested === null) {
            return $cache[$file];
        }

        $value = $cache[$file];
        foreach (explode('.', $nested) as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
                continue;
            }

            return $default;
        }

        return $value;
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = [], ?string $layout = 'layouts/main'): string
    {
        return Core\View::render($template, $data, $layout);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $version = config('app.assets_version', '1');
        $url = rtrim(config('app.url', ''), '/') . '/';
        $path = ltrim($path, '/');

        return $url . $path . '?v=' . urlencode((string) $version);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('session')) {
    function session(): Session
    {
        return Session::instance();
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $value): void
    {
        session()->flash($key, $value);
    }
}

if (!function_exists('theme_palette')) {
    function theme_palette(): array
    {
        return ThemeManager::palette();
    }
}

if (!function_exists('theme_meta')) {
    function theme_meta(): array
    {
        return ThemeManager::themeMeta();
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);
        if ($value === false || $value === null) {
            return $default;
        }

        if (is_string($default)) {
            return (string) $value;
        }

        if (is_int($default)) {
            return (int) $value;
        }

        if (is_bool($default)) {
            return in_array(strtolower((string) $value), ['1', 'true', 'on', 'yes'], true);
        }

        return $value;
    }
}
