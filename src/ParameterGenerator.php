<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use OutOfBoundsException;
use ReflectionClass;
use WellRESTed\OpenAPI\Components\Parameter;
use WellRESTed\Routing\Route\Route;
use WellRESTed\OpenAPI\Attributes\Param;

class ParameterGenerator
{
    /** @return Parameter[] */
    public function generate(string $method, Route $route): array
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
