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
class OpenAPIYamlHandler implements RequestHandlerInterface
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
        $doc = $this->encoder->encodeDocument($this->server);
        $representaion = $this->encoder->documentToArray($doc);

        $body = yaml_emit($representaion);

        return (new Response(200))
            ->withHeader('Content-type', 'application/yaml')
            ->withBody(new Stream($body));
    }
}
