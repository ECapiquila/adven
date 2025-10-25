<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\ThemeManager;
use Core\Controller;
use Core\Http\Request;
use Core\Http\Response;

final class PwaController extends Controller
{
    public function manifest(Request $request): Response
    {
        $palette = ThemeManager::palette();
        $theme = ThemeManager::themeMeta();
        $iconKey = $theme['key'] ?? 'desbravadores';
        $availableIcons = ['desbravadores', 'embaixadores', 'jovens'];
        if (!in_array($iconKey, $availableIcons, true)) {
            $iconKey = 'desbravadores';
        }
        $iconPath = '/assets/icons/' . $iconKey . '.svg';

        $manifest = [
            'name' => config('app.name', 'Sabbath Connect'),
            'short_name' => 'Sabbath',
            'start_url' => config('app.pwa.start_url', '/feed'),
            'display' => config('app.pwa.display', 'standalone'),
            'background_color' => $palette['light'] ?? '#ffffff',
            'theme_color' => $palette['primary'] ?? '#0d47a1',
            'description' => 'Rede social adventista com foco em espiritualidade, louvores e comunidade.',
            'icons' => [
                [
                    'src' => $iconPath,
                    'type' => 'image/svg+xml',
                    'sizes' => '96x96 180x180 192x192 512x512',
                    'purpose' => 'any maskable',
                ],
            ],
            'shortcuts' => [
                ['name' => 'Postar', 'url' => '/feed?create=post'],
                ['name' => 'Oração', 'url' => '/oracoes/novo'],
                ['name' => 'Louvores', 'url' => '/louvores'],
            ],
            'categories' => ['social', 'productivity', 'religion'],
            'related_applications' => [],
            'scope' => '/',
            'prefer_related_applications' => false,
            'id' => 'sabbath-connect',
            'theme' => $theme,
        ];

        return Response::json($manifest);
    }
}
