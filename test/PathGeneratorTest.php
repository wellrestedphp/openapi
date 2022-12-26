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

    /** @dataProvider methodProvider */
    public function testIncludesOperationsForSupportedMethods(
        string $registerMethods,
        array $expected
    ): void {
        // Arrange
        $router = $this->server->createRouter();
        $router->register($registerMethods, '/path/', new Response(200));
        $route = $router->getRoutes()['/path/'];

        // Act
        $path = $this->generator->generate($route);

        // Assert
        foreach ($expected as $method) {
            $this->assertNotNull($path->{$method}, "Should have had operation for $method");
        }
        $allMethods = ['get', 'post', 'put', 'delete', 'options', 'head', 'patch', 'trace'];
        $expectedNull = array_diff($allMethods, $expected);
        foreach ($expectedNull as $method) {
            $this->assertNull($path->{$method}, "Should not have had operstion for $method");
        }
    }

    public function methodProvider(): array
    {
        return [
            ['GET,POST', ['get', 'post']],
            ['GET,PUT,DELETE', ['get', 'put', 'delete']]
        ];
    }
}
