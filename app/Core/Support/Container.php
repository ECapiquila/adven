<?php

namespace App\Core\Support;

use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, callable $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, callable $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
        $this->instances[$abstract] = null;
    }

    public function make(string $abstract)
    {
        if (array_key_exists($abstract, $this->instances) && $this->instances[$abstract] !== null) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            $object = $this->bindings[$abstract]($this);
            if (array_key_exists($abstract, $this->instances)) {
                $this->instances[$abstract] = $object;
            }

            return $object;
        }

        if (!class_exists($abstract)) {
            throw new RuntimeException("Class {$abstract} not bound to container");
        }

        $reflection = new ReflectionClass($abstract);
        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Class {$abstract} is not instantiable");
        }

        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return new $abstract();
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new RuntimeException("Cannot resolve dependency for {$parameter->getName()} on {$abstract}");
            }
            $dependencies[] = $this->make($type->getName());
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
