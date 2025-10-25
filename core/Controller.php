<?php

declare(strict_types=1);

namespace Core;

use Core\Http\Request;

abstract class Controller
{
    protected function view(string $template, array $data = [], ?string $layout = 'layouts/main'): string
    {
        return View::render($template, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        \redirect($path);
    }

    protected function requestData(Request $request, array $fields): array
    {
        return $request->only($fields);
    }
}
