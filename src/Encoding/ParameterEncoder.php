<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use OutOfBoundsException;
use WellRESTed\OpenAPI\Components\In;
use WellRESTed\OpenAPI\Components\Parameter;
use WellRESTed\Routing\Route\Route;

class ParameterEncoder
{
    private ReflectionResolver $reflectionResolver;

    public function __construct(ReflectionResolver $reflectionResolver)
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    /** @return Parameter[] */
    public function encode(string $method, Route $route): array
    {
        return array_merge(
            $this->getParametersFromTarget($route),
            $this->getParametersFromAttributes($method, $route)
        );
    }

    /** @return Parameter[] */
    private function getParametersFromTarget(Route $route): array
    {
        $target = $route->getTarget();

        $names = [];
        $pattern = '~\{(.*)\}~U';
        if (preg_match_all($pattern, $target, $matches)) {
            $names = $matches[1] ?? [];
        }

        return array_map(function (string $name): Parameter {
            $param = new Parameter($name, In::PATH);
            return $param;
        }, $names);
    }

    /** @return Parameter[] */
    private function getParametersFromAttributes(string $method, Route $route): array
    {
        $params = [];

        $methods = $route->getMethods();
        $handler = $methods[$method] ?? throw new OutOfBoundsException();
        $reflections = $this->reflectionResolver->getReflections($handler);
        foreach ($reflections as $reflection) {
            $atts = $reflection->getAttributes(Parameter::class);
            foreach ($atts as $att) {
                $params[] = $att->newInstance();
            }
        }

        return $params;
    }
}
