<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use OutOfBoundsException;
use ReflectionClass;
use WellRESTed\OpenAPI\Attributes\Param;
use WellRESTed\OpenAPI\Components\In;
use WellRESTed\OpenAPI\Components\Parameter;
use WellRESTed\Routing\Route\Route;

class ParameterGenerator
{
    /** @return Parameter[] */
    public function generate(string $method, Route $route): array
    {
        return array_merge(
            $this->getParametersFromPath($route),
            $this->getParametersFromAttributes($method, $route)
        );
    }

    /** @return Parameter[] */
    private function getParametersFromPath(Route $route): array
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
        $methods = $route->getMethods();
        $handler = $methods[$method] ?? throw new OutOfBoundsException();
        $reflection = new ReflectionClass($handler);

        $params = [];

        $atts = $reflection->getAttributes(Param::class);
        foreach ($atts as $att) {
            $attInstance = $att->newInstance();
            $param = new Parameter($attInstance->name, $attInstance->in);
            $params[] = $param;
        }

        return $params;
    }
}
