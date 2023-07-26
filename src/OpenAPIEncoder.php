<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\OpenAPI;
use WellRESTed\OpenAPI\Encoding\PathEncoder;
use WellRESTed\OpenAPI\Encoding\ReflectionResolver;
use WellRESTed\Routing\Router;
use WellRESTed\Server;

class OpenAPIEncoder
{
    public function encode(Server $server): OpenAPI
    {
        $resolver = new ReflectionResolver($server);
        $pathEncoder = new PathEncoder($resolver);

        $doc = new OpenAPI();
        $middlewareQueue = $server->getMiddleware();

        foreach ($middlewareQueue as $middleware) {
            if ($middleware instanceof Router) {
                $routes = $middleware->getRoutes();
                foreach ($routes as $target => $route) {
                    $doc->paths[$target] = $pathEncoder->encode($route);
                }
            }
        }

        return $doc;
    }
}
