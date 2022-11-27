<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\Stream;
use WellRESTed\OpenAPI\Components\Parameter;

#[Parameter('breed')]
class DogsHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dogs = [
            new Dog('Louisa')
        ];
        return (new Response(200))
            ->withHeader('Content-type', 'application/json')
            ->withBody(new Stream(json_encode($dogs, JSON_PRETTY_PRINT)));
    }
}
