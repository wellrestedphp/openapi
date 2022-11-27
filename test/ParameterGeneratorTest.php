<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Attributes\Param;
use WellRESTed\OpenAPI\Components\In;
use WellRESTed\Routing\Route\Route;
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

    public function testShouldProvideParameterFromPathTemplate(): void
    {
        // Arrange
        $route = $this->createRoute('/path/{foo}/{bar}');

        // Act
        $params = $this->generate('GET', $route);

        // Assert
        $this->assertCount(2, $params);
        $map = $this->mapByName($params);
        $this->assertArrayHasKey('foo', $map);
        $this->assertArrayHasKey('bar', $map);
    }

    private function generate(string $method, Route $route): array
    {
        $paramGen = new ParameterGenerator();
        return $paramGen->generate($method, $route);
    }

    private function createRoute(string $path, mixed $handler = null): Route
    {
        $handler ??= new class () {};

        $server = new Server();
        $router = $server->createRouter();
        $router->register('GET', $path, $handler);
        return $router->getRoutes()[$path];
    }

    private function mapByName(array $parameters): array
    {
        $map = [];
        foreach ($parameters as $param) {
            $map[$param->name] = $param;
        }
        return $map;
    }
}
