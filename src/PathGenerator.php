<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\Operation;
use WellRESTed\OpenAPI\Components\Path;
use WellRESTed\Routing\Route\Route;

class PathGenerator
{
    private ReflectionResolver $reflectionResolver;

    public function __construct(ReflectionResolver $reflectionResolver)
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    public function generate(Route $route): Path
    {
        $path = new Path();
        $path->get = $this->generateOperation('GET', $route);
        $path->post = $this->generateOperation('POST', $route);

        return $path;
    }

    private function generateOperation(string $method, Route $route): ?Operation
    {
        $handler = $route->getMethods()[$method] ?? null;

        if (!$handler) {
            return null;
        }

        $operation = new Operation();

        $paramGen = new ParameterGenerator($this->reflectionResolver);
        $params = $paramGen->generate($method, $route);
        $operation->parameters = $params;

        $responseGen = new ResponseGenerator($this->reflectionResolver);
        $operation->responses = $responseGen->generate($handler);

        return $operation;
    }
}
