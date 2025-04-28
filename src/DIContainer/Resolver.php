<?php

namespace ScoobEco\DIContainer;

use AllowDynamicProperties;
use ReflectionClass;
use Exception;

/**
 * @template T
 * @mixin T
 */
#[AllowDynamicProperties]
class Resolver
{
    /** @var T */
    public object $instance;

    public function __construct(string $class)
    {
        $this->instance = $this->resolve($class);
    }

    private function resolve(string $class): object
    {
        $reflectionClass = new ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $class();
        }

        $parameters   = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType();

            if ($dependency === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                }
                else {
                    throw new Exception("Não é possível resolver a dependência '{$parameter->getName()}' de '{$class}'");
                }
            }
            else {
                $dependencies[] = $this->resolve($dependency->getName());
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function __call(string $method, array $arguments)
    {
        return $this->instance->$method(...$arguments);
    }
}
