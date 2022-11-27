<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use Closure;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use WellRESTed\Server;
use WellRESTed\ServerReferenceTrait;

class ReflectionResolver
{
    use ServerReferenceTrait;

    public function __construct(Server $server)
    {
        $this->setServer($server);
    }

    /** @return ReflectionClass[] */
    public function getReflections(mixed $handler): array
    {
        $reflections = [];

        if (is_string($handler)) {
            if (class_exists($handler)) {
                $reflections[] = $this->fromClass($handler);
            } else {
                $reflections[] = $this->fromService($handler);
            }
        } elseif ($handler instanceof Closure) {
            $reflections[] = $this->fromClosure($handler);
        } elseif (is_array($handler)) {
            foreach ($handler as $item) {
                $reflections = array_merge($reflections, $this->getReflections($item));
            }
        } elseif (is_object($handler)) {
            $reflections[] = $this->fromClass($handler);
        }

        return array_filter($reflections);
    }

    private function fromClass(string|object $handler): ?ReflectionClass
    {
        $reflection = null;

        if (is_string($handler) && class_exists($handler) || is_object($handler)) {
            $reflection = new ReflectionClass($handler);
        }

        if ($reflection && $reflection->implementsInterface(ResponseInterface::class)) {
            return null;
        }
        return $reflection;
    }

    private function fromService(string $serviceName): ?ReflectionClass
    {
        $container = $this->getServer()->getContainer();
        if ($container && $container->has($serviceName)) {
            $service = $container->get($serviceName);
            return $this->fromClass($service);
        }
        return null;
    }

    private function fromClosure(Closure $handler): ?ReflectionClass
    {
        $reflectionFn = new ReflectionFunction($handler);
        $returnType = $reflectionFn->getReturnType();
        if ($returnType instanceof ReflectionNamedType) {
            $name = $returnType->getName();
            return $this->fromClass($name);
        }
        return null;
    }
}
