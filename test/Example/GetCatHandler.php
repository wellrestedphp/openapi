<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\Stream;
use WellRESTed\OpenAPI\Attributes\StatusCode;

#[StatusCode(200)]
#[StatusCode(404)]
class GetCatHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $cat = new Cat('Molly');
        return (new Response(200))
            ->withHeader('Content-type', 'application/json')
            ->withBody(new Stream(json_encode($cat, JSON_PRETTY_PRINT)));
    }
}
