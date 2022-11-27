<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\Stream;
use WellRESTed\OpenAPI\Attributes\StatusCode;
use WellRESTed\OpenAPI\Components\Parameter;

#[StatusCode(200, description: 'List of cats')]
#[StatusCode(403, description: 'User does not have access')]
#[Parameter('color')]
class CatsHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $cats = [
            new Cat('Molly'),
            new Cat('Oscar'),
            new Cat('Aggie')
        ];
        return (new Response(200))
            ->withHeader('Content-type', 'application/json')
            ->withBody(new Stream(json_encode($cats, JSON_PRETTY_PRINT)));
    }
}
