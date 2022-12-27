<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use WellRESTed\OpenAPI\Components\In;
use WellRESTed\OpenAPI\Components\Parameter;
use WellRESTed\Routing\Route\Route;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class ParameterEncoderTest extends TestCase
{
    private Server $server;
    private ParameterEncoder $encoder;

    public function setUp(): void
    {
        parent::setUp();

        $this->server = new Server();
        $resolver = new Reflectionresolver($this->server);
        $this->encoder = new ParameterEncoder($resolver);
    }

    public function testIncludesParametersFromAttributes(): void
    {
        // Arrange
        $handler = new #[Parameter('color', In::QUERY)] class {};
        $route = $this->createRoute('/cats/', $handler);

        // Act
        $params = $this->generate('GET', $route);

        // Assert
        $this->assertCount(1, $params);
        $param = $params[0];
        $this->assertEquals('color', $param->name);
        $this->assertEquals(In::QUERY, $param->in);
    }

    public function testIncludesParametersFromPathTemplate(): void
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
        return $this->encoder->encode($method, $route);
    }

    private function createRoute(string $path, mixed $handler = null): Route
    {
        $handler ??= new class () {};

        $router = $this->server->createRouter();
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
