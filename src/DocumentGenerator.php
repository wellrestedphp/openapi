<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\Document;
use WellRESTed\OpenAPI\Components\Path;
use WellRESTed\Routing\Router;
use WellRESTed\Server;

class DocumentGenerator
{
    public function generate(Server $server): Document
    {
        $doc = new Document();

        $middlewareQueue = $server->getMiddleware();

        foreach ($middlewareQueue as $middleware) {
            if ($middleware instanceof Router) {
                $routes = $middleware->getRoutes();

                /** var Route $route */
                foreach ($routes as $target => $route) {

                    $doc->paths[$target] = new Path();

                }
            }
        }

        return $doc;
    }
}
