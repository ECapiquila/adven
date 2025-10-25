<?php

declare(strict_types=1);

namespace Core;

final class View
{
    public static function render(string $template, array $data = [], ?string $layout = 'layouts/main'): string
    {
        $path = self::viewPath($template);
        if (!is_file($path)) {
            throw new \InvalidArgumentException(sprintf('View "%s" não encontrada.', $template));
        }

        extract($data, EXTR_SKIP);
        $palette = theme_palette();
        $flash = session()->flushFlash();

        ob_start();
        require $path;
        $content = ob_get_clean();

        if ($layout === null) {
            return $content;
        }

        $layoutPath = self::viewPath($layout);
        if (!is_file($layoutPath)) {
            throw new \InvalidArgumentException(sprintf('Layout "%s" não encontrado.', $layout));
        }

        ob_start();
        require $layoutPath;

        return (string) ob_get_clean();
    }

    private static function viewPath(string $template): string
    {
        $template = str_replace(['::', '.'], ['/', '/'], $template);

        return base_path('resources/views/' . $template . '.php');
    }
}
