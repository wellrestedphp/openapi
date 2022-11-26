<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\Stream;
use WellRESTed\OpenAPI\Attributes\StatusCode;
use WellRESTed\OpenAPI\DocumentGenerator;
use WellRESTed\Server;

#[StatusCode(200)]
class OpenAPIDocumentHandler implements RequestHandlerInterface
{
    private Server $server;
    private DocumentGenerator $generator;

    public function __construct(
        Server $server,
        DocumentGenerator $generator
    ) {
        $this->server = $server;
        $this->generator = $generator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $document = $this->generator->generate($this->server);

        return (new Response(200))
            ->withHeader('Content-type', 'application/json')
            ->withBody(new Stream(json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)));
    }
}
