<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\OpenAPI\Components\StatusCode;

#[StatusCode(200)]
#[StatusCode(403, description: "User does not have permission to access")]
class ProtectedHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200);
    }
}
