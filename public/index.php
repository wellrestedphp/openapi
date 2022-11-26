<?php

declare(strict_types=1);

use WellRESTed\Message\Response;
use WellRESTed\OpenAPI\Example\CatsHandler;
use WellRESTed\OpenAPI\Example\GetCatHandler;
use WellRESTed\OpenAPI\Example\DogsHandler;
use WellRESTed\OpenAPI\Example\OpenAPIDocumentHandler;
use WellRESTed\Server;

require_once '../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    Server::class => function (DI\Container $c): Server {

        $server = new Server();
        $server->setContainer($c);
        $router = $server->createRouter();

        $router->register('GET', '/', new Response(200));
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
$container = $builder->build();

$server = $container->get(Server::class);
$server->respond();
