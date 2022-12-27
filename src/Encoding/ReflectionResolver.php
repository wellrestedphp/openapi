<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use Closure;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use WellRESTed\Server;
use WellRESTed\ServerReferenceTrait;

/**
 * Locates ReflectionClasses for handlers.
 *
 * WellRESTed dispatches handlers and middlewware from instances, class names,
 * DI services, factory functions, arrays, etc. ReflectionResolver accepts
 * these as inputs and returns an array of ReflectionClass instances for
 * classes associated with the handler and middleware.
 */
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

        if (is_array($handler)) {
            $reflections = [...$reflections, ...$this->fromArray($handler)];
        } elseif (is_string($handler)) {
            $reflections[] = $this->fromString($handler);
        } elseif ($handler instanceof Closure) {
            $reflections[] = $this->fromClosure($handler);
        } elseif (is_object($handler)) {
            $reflections[] = $this->fromClass($handler);
        }

        return $this->filter($reflections);
    }

    /**
     * A route may have an array of middleware and handlers associated. Locate
     * each middleware and handler and return an array.
     *
     * @return ReflectionClass[]
     */
    private function fromArray(array $handler): array
    {
        $reflections = [];

        foreach ($handler as $item) {
            $reflections = [...$reflections, ...$this->getReflections($item)];
        }

        return $reflections;
    }

    private function fromString(string $handler): ?ReflectionClass
    {
        return class_exists($handler)
            ? $this->fromClass($handler)
            : $this->fromService($handler);
    }

    /**
     * Create the ReflectionClass for a handler from an instance or class name.
     */
    private function fromClass(string|object $handler): ?ReflectionClass
    {
        $reflection = null;

        if (is_string($handler) && class_exists($handler) || is_object($handler)) {
            $reflection = new ReflectionClass($handler);
        }

        return $reflection;
    }

    /**
     * Locate the handler by service name from the DI container (if present).
     */
    private function fromService(string $serviceName): ?ReflectionClass
    {
        $reflection = null;
        $container = $this->getServer()->getContainer();

        if ($container && $container->has($serviceName)) {
            $service = $container->get($serviceName);
            $reflection = $this->fromClass($service);
        }

        return $reflection;
    }

    /**
     * Handlers may be registered with factory functions. When the function has
     * a return type, use that to determing the handler's class.
     */
    private function fromClosure(Closure $handler): ?ReflectionClass
    {
        $reflection = null;
        $reflectionFn = new ReflectionFunction($handler);
        $returnType = $reflectionFn->getReturnType();

        if ($returnType instanceof ReflectionNamedType) {
            $name = $returnType->getName();
            $reflection = $this->fromClass($name);
        }

        return $reflection;
    }

    private function filter(array $reflections): array
    {
        $fn = function (?ReflectionClass $reflection): bool {
            return $reflection && !$reflection->implementsInterface(ResponseInterface::class);
        };

        return array_filter($reflections, $fn);
    }
}
