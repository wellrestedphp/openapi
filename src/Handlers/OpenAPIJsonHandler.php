<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\Stream;
use WellRESTed\OpenAPI\Components\StatusCode;
use WellRESTed\OpenAPI\OpenAPIEncoder;
use WellRESTed\Server;

#[StatusCode(200)]
class OpenAPIJsonHandler implements RequestHandlerInterface
{
    private Server $server;
    private OpenAPIEncoder $encoder;

    public function __construct(
        Server $server,
        OpenAPIEncoder $encoder
    ) {
        $this->server = $server;
        $this->encoder = $encoder;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $openApi = $this->encoder->encode($this->server);
        $body = json_encode($openApi->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return (new Response(200))
            ->withHeader('Content-type', 'application/json')
            ->withBody(new Stream($body));
    }
}
