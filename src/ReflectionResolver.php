<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use Closure;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionFunction;

class ReflectionResolver
{
    /** @return RefrlectionClass[] */
    public function getReflections(mixed $handler): array
    {
        $reflections = [];

        if (is_string($handler)) {
            $reflections[] = $this->getReflection($handler);
        } elseif ($handler instanceof Closure) {
            $reflections[] = $this->getReflectionFromClosure($handler);
        } elseif (is_array($handler)) {
            foreach ($handler as $item) {
                $reflections = array_merge($reflections, $this->getReflections($item));
            }
        } elseif (is_object($handler)) {
            $reflections[] = $this->getReflection($handler);
        }

        return array_filter($reflections);
    }

    private function getReflection(mixed $handler): ?ReflectionClass
    {
        $reflection = new ReflectionClass($handler);
        if ($reflection->implementsInterface(ResponseInterface::class)) {
           return null;
        }
        return $reflection;
    }

    private function getReflectionFromClosure(Closure $handler): ?ReflectionClass
    {
        $reflectionFn = new ReflectionFunction($handler);
        $returnType = $reflectionFn->getReturnType();
        if ($returnType) {
            return new ReflectionClass($returnType->getName());
        }
        return null;
    }
}
