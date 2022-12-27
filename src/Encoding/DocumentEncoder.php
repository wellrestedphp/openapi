<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use WellRESTed\OpenAPI\Components\Document;
use WellRESTed\Routing\Router;
use WellRESTed\Server;

class DocumentEncoder
{
    public function encode(Server $server): Document
    {
        $doc = new Document();

        $reflectionResolver = new ReflectionResolver($server);

        $pathGen = new PathEncoder($reflectionResolver);

        $middlewareQueue = $server->getMiddleware();

        foreach ($middlewareQueue as $middleware) {
            if ($middleware instanceof Router) {
                $routes = $middleware->getRoutes();

                /** var Route $route */
                foreach ($routes as $target => $route) {
                    $doc->paths[$target] = $pathGen->encode($route);
                }
            }
        }

        return $doc;
    }
}
