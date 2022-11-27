<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\In;
use WellRESTed\OpenAPI\Attributes\Param;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class ParameterGeneratorTest extends TestCase
{
    public function testIncludesParametersFromAttributes(): void
    {
        // Arrange
        $handler = new #[Param('color', In::QUERY)] class {};

        $server = new Server();
        $router = $server->createRouter();
        $router->register('GET', '/cats/', $handler);
        $route = $router->getRoutes()['/cats/'];

        // Act
        $paramGen= new ParameterGenerator();
        $params = $paramGen->generate('GET', $route);

        // Assert
        $this->assertCount(1, $params);
        $param = $params[0];
        $this->assertEquals('color', $param->name);
        $this->assertEquals(In::QUERY, $param->in);
    }
}

