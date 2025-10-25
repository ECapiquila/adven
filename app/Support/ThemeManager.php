<?php

declare(strict_types=1);

namespace App\Support;

use Core\Database;
use PDO;
use PDOException;

final class ThemeManager
{
    private const DEFAULT_THEME_SETTING = 'pwa_theme_active';
    private const CUSTOM_PALETTE_SETTING = 'pwa_theme_custom';

    public static function activeTheme(): string
    {
        $fromDb = self::getSetting(self::DEFAULT_THEME_SETTING);
        if (is_string($fromDb) && $fromDb !== '') {
            return $fromDb;
        }

        return config('app.pwa.default_theme', 'desbravadores');
    }

    public static function palette(): array
    {
        $theme = self::activeTheme();
        $themes = config('app.pwa.themes', []);

        if ($theme === 'custom') {
            $custom = self::getSetting(self::CUSTOM_PALETTE_SETTING);
            if (is_string($custom) && $custom !== '') {
                $decoded = json_decode($custom, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        return $themes[$theme]['palette'] ?? ($themes['desbravadores']['palette'] ?? []);
    }

    public static function themeMeta(): array
    {
        $theme = self::activeTheme();
        $themes = config('app.pwa.themes', []);

        if ($theme === 'custom') {
            return [
                'key' => 'custom',
                'label' => 'Tema Personalizado',
                'palette' => self::palette(),
            ];
        }

        if (isset($themes[$theme])) {
            return ['key' => $theme] + $themes[$theme];
        }

        return ['key' => 'desbravadores'] + ($themes['desbravadores'] ?? []);
    }

    private static function getSetting(string $key): mixed
    {
        try {
            $pdo = Database::connection();
            $statement = $pdo->prepare('SELECT value FROM settings WHERE `key` = :key LIMIT 1');
            $statement->execute(['key' => $key]);
            $value = $statement->fetch(PDO::FETCH_ASSOC);
            if ($value === false) {
                return null;
            }

            return $value['value'] ?? null;
        } catch (PDOException|\RuntimeException) {
            return null;
        }
    }
}
