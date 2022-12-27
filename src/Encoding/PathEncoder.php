<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use WellRESTed\OpenAPI\Components\Operation;
use WellRESTed\OpenAPI\Components\Path;
use WellRESTed\Routing\Route\Route;

class PathEncoder
{
    /** Methods described by OpenAPI */
    private const METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD', 'PATCH', 'TRACE'];
    private ReflectionResolver $reflectionResolver;

    public function __construct(ReflectionResolver $reflectionResolver)
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    public function encode(Route $route): Path
    {
        $path = new Path();

        foreach (self::METHODS as $method) {
            $operation = strtolower($method);
            $path->{$operation} = $this->generateOperation($method, $route);
        }

        return $path;
    }

    private function generateOperation(string $method, Route $route): ?Operation
    {
        $handler = $route->getMethods()[$method] ?? null;

        if (!$handler) {
            return null;
        }

        $operation = new Operation();

        $paramGen = new ParameterEncoder($this->reflectionResolver);
        $params = $paramGen->encode($method, $route);
        $operation->parameters = $params;

        $responseGen = new ResponseEncoder($this->reflectionResolver);
        $operation->responses = $responseGen->encode($handler);

        return $operation;
    }
}
