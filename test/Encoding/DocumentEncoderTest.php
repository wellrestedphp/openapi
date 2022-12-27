<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use WellRESTed\Message\Response;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class DocumentEncoderTest extends TestCase
{
    public function testIncludesPathForEachEndpoint(): void
    {
        // Arrange
        $server = new Server();
        $router = $server->createRouter();
        $router->register('GET', '/cats/', new Response(200));
        $router->register('GET', '/dogs/', new Response(200));
        $server->add($router);

        // Act
        $encoder = new DocumentEncoder();
        $doc = $encoder->encode($server);

        // Assert
        $this->assertArrayHasKey('/cats/', $doc->paths);
        $this->assertArrayHasKey('/dogs/', $doc->paths);
    }
}
