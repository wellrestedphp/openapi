<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\OpenAPI\Components\StatusCode;

#[StatusCode(401, description: 'User is not authenticated')]
class TokenMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        return $handler->handle($request);
    }
}
