<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\Stream;
use WellRESTed\OpenAPI\Components\StatusCode;
use WellRESTed\OpenAPI\DocumentGenerator;
use WellRESTed\OpenAPI\Encoding\PrimativeEncoder;
use WellRESTed\Server;

#[StatusCode(200)]
class OpenAPIYamlHandler implements RequestHandlerInterface
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
        $converter = new PrimativeEncoder();
        $representaion = $converter->encode($document);

        $body = yaml_emit($representaion);

        return (new Response(200))
            ->withHeader('Content-type', 'application/yaml')
            ->withBody(new Stream($body));
    }
}
