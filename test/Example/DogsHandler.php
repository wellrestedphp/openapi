<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\Stream;
use WellRESTed\OpenAPI\Attributes\StatusCode;

#[StatusCode(200, description: 'List of dogs')]
#[StatusCode(403, description: 'User does not have access')]
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
