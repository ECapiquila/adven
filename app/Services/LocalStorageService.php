<?php

namespace App\Services;

class LocalStorageService
{
    private string $basePath = __DIR__ . '/../../storage/uploads';

    public function put(string $path, array $file): ?string
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        $target = $this->basePath . '/' . ltrim($path, '/');
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }
        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $target;
        }

        return null;
    }
}
