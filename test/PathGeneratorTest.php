<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\Message\Response;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class PathGeneratorTest extends TestCase
{
    private Server $server;
    private PathGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $this->server = new Server();
        $resolver = new Reflectionresolver($this->server);
        $this->generator = new PathGenerator($resolver);
    }

    public function testIncludesOperationsForSupportedMethods(): void
    {
        // Arrange
        $router = $this->server->createRouter();
        $router->register('GET,POST', '/cats/', new Response(200));
        $route = $router->getRoutes()['/cats/'];

        // Act
        $path = $this->generator->generate($route);

        // Assert
        $this->assertNotNull($path->get);
        $this->assertNotNull($path->post);
    }
}
