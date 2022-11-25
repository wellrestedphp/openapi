<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\Message\Response;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class DocumentGeneratorTest extends TestCase
{
    public function testGeneratesPathForEachEndpoint(): void
    {
        // Arrange
        $server = new Server();
        $router = $server->createRouter();
        $router->register('GET', '/cats/', new Response(200));
        $router->register('GET', '/dogs/', new Response(200));
        $server->add($router);

        // Act
        $docGen = new DocumentGenerator();
        $doc = $docGen->generate($server);

        // Assert
        $this->assertArrayHasKey('/cats/', $doc->paths);
        $this->assertArrayHasKey('/dogs/', $doc->paths);
    }
}
