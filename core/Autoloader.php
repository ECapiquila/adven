<?php

namespace Core;

final class Autoloader
{
    /** @var array<string,string> */
    private array $prefixes = [];

    public static function register(array $prefixes): self
    {
        $loader = new self();
        foreach ($prefixes as $prefix => $path) {
            $loader->addNamespace($prefix, $path);
        }

        spl_autoload_register([$loader, 'load']);

        return $loader;
    }

    public function addNamespace(string $prefix, string $path): void
    {
        $prefix = trim($prefix, '\\') . '\\';
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->prefixes[$prefix] = $path;
    }

    private function load(string $class): void
    {
        foreach ($this->prefixes as $prefix => $baseDir) {
            if (str_starts_with($class, $prefix)) {
                $relative = substr($class, strlen($prefix));
                $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relative);
                $file = $baseDir . $relativePath . '.php';

                if (is_file($file)) {
                    require $file;
                }

                return;
            }
        }
    }
}
