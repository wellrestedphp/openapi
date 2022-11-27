<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use DI;
use Psr\Container\ContainerInterface;
use WellRESTed\Server;

class ContainerBuilder
{
    public function build(): ContainerInterface
    {
        $builder = new DI\ContainerBuilder();
        $builder->addDefinitions([
            Server::class => function (DI\Container $c): Server {
                $server = new Server();
                $server->setContainer($c);
                $router = $server->createRouter();

                $router->register('GET,POST', '/cats/', CatsHandler::class);
                $router->register('GET', '/cats/{id}', GetCatHandler::class);
                $router->register('GET,POST', '/dogs/', DogsHandler::class);
                $router->register('GET', '/openapi.json', OpenAPIDocumentHandler::class);

                $server->add($router);

                return $server;
            },
            CatsHandler::class => DI\autowire(),
            DogsHandler::class => DI\autowire(),
            OpenAPIDocumentHandler::class => DI\autowire()
        ]);
        return $builder->build();
    }
}