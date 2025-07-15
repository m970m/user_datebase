<?php
declare(strict_types=1);

namespace App\Container;

class Container
{
    private array $entries;

    public function set(string $id, string|callable $concrete)
    {
        $this->entries[$id] = $concrete;
    }

    public function get(string $id)
    {
        if (!$this->has($id))
        {
            return $this->resolve($id);
        }

        $entry = $this->entries[$id];

        if (is_callable($entry))
        {
            return $entry($this);
        }

        return $this->resolve($entry);
    }

    public function has(string $id)
    {
        return isset($this->entries[$id]);
    }

    private function resolve(string $id) {
        $reflectionClass = new \ReflectionClass($id);

        if (!$reflectionClass->isInstantiable()) {
            throw new \InvalidArgumentException("Class is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $id;
        }

        $parameters = $constructor->getParameters();

        $args = [];
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if (!$type || $type->isBuiltin()) {
                throw new \InvalidArgumentException("Invalid constructor params");
            }
            $name = $type->getName();
            $args[] = $this->get($name);
        }

        return $reflectionClass->newInstanceArgs($args);
    }
}