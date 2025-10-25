<?php

declare(strict_types=1);

return [
    'name' => 'Sabbath Connect',
    'env' => 'local',
    'debug' => true,
    'url' => 'https://sabbathconnect.local',
    'timezone' => 'Africa/Luanda',
    'assets_version' => 1,
    'notifications' => [
        'inapp_enabled' => true,
        'email_enabled' => true,
        'transport' => 'polling',
        'sse_enabled' => false,
        'poll_interval' => 30,
    ],
    'pwa' => [
        'start_url' => '/feed',
        'display' => 'standalone',
        'background_sync_enabled' => true,
        'theme_sync_enabled' => true,
        'themes' => [
            'desbravadores' => [
                'label' => 'Desbravadores',
                'palette' => [
                    'primary' => '#004884',
                    'secondary' => '#f9a825',
                    'accent' => '#f4511e',
                    'light' => '#f5f5f5',
                    'dark' => '#102027',
                    'muted' => '#607d8b',
                    'success' => '#2e7d32',
                    'warning' => '#ffb300',
                    'info' => '#0288d1',
                ],
            ],
            'embaixadores' => [
                'label' => 'Embaixadores',
                'palette' => [
                    'primary' => '#0d47a1',
                    'secondary' => '#1976d2',
                    'accent' => '#ff7043',
                    'light' => '#fafafa',
                    'dark' => '#0a1929',
                    'muted' => '#78909c',
                    'success' => '#388e3c',
                    'warning' => '#fbc02d',
                    'info' => '#26a69a',
                ],
            ],
            'jovens' => [
                'label' => 'Jovens',
                'palette' => [
                    'primary' => '#6a1b9a',
                    'secondary' => '#9c27b0',
                    'accent' => '#ff4081',
                    'light' => '#f3e5f5',
                    'dark' => '#311b92',
                    'muted' => '#9575cd',
                    'success' => '#43a047',
                    'warning' => '#ffca28',
                    'info' => '#26c6da',
                ],
            ],
        ],
        'default_theme' => 'desbravadores',
    ],
    'dark_mode' => [
        'enabled' => true,
        'default' => 'system',
    ],
    'vapid' => [
        'public_key' => 'BPD7oVjnydJzK0iY4G0-ExamplePublicKeyGeneratedForDemo',
        'private_key' => 'NCaPS3ExamplePrivateKeyReplaceWithRealOne',
    ],
];
