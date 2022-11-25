<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\Operation;
use WellRESTed\OpenAPI\Components\Path;
use WellRESTed\Routing\Route\Route;

class PathGenerator
{
    public function generate(Route $route): Path
    {
        $methods = $route->getMethods();

        $path = new Path();
        $path->get = $this->generateOperation($methods['GET'] ?? null);
        $path->post = $this->generateOperation($methods['POST'] ?? null);

        return $path;
    }

    private function generateOperation(mixed $handler): ?Operation
    {
        if (!$handler) {
            return null;
        }
        return new Operation();
    }
}
