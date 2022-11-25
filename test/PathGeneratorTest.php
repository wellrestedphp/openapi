<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\Message\Response;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class PathGeneratorTest extends TestCase
{
    public function testIncludesOperationsForSupportedMethods(): void
    {
        // Arrange
        $server = new Server();
        $router = $server->createRouter();
        $router->register('GET,POST', '/cats/', new Response(200));
        $route = $router->getRoutes()['/cats/'];

        // Act
        $pathGen = new PathGenerator();
        $path = $pathGen->generate($route);

        // Assert
        $this->assertNotNull($path->get);
        $this->assertNotNull($path->post);
    }
}
