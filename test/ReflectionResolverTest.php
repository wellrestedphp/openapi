<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\Message\Response;
use WellRESTed\OpenAPI\Example;
use WellRESTed\Test\TestCase;

class ReflectionResolverTest extends TestCase
{
    public function testResolvesReflectorFromClassName(): void
    {
        // Arrange
        $handler = Example\CatsHandler::class;

        // Act
        $reflectors = $this->resolve($handler);

        // Assert
        $this->assertCount(1, $reflectors);
        $this->assertEquals($reflectors[0]->getName(), Example\CatsHandler::class);
    }

    public function testResolvesReflectorFromInstance(): void
    {
        // Arrange
        $handler = new Example\CatsHandler();

        // Act
        $reflectors = $this->resolve($handler);

        // Assert
        $this->assertCount(1, $reflectors);
        $this->assertEquals($reflectors[0]->getName(), Example\CatsHandler::class);
    }

    public function testResolvesReflectorFromFactoryFunction(): void
    {
        // Arrange
        $handler = fn(): Example\CatsHandler => new Example\CatsHandler();

        // Act
        $reflectors = $this->resolve($handler);

        // Assert
        $this->assertCount(1, $reflectors);
        $this->assertEquals($reflectors[0]->getName(), Example\CatsHandler::class);
    }

    public function testDoesNotResolveReflectorFromResponse(): void
    {
        // Arrange
        $handler = new Response(200);

        // Act
        $reflectors = $this->resolve($handler);

        // Assert
        $this->assertCount(0, $reflectors);
    }

    public function testWhenHandlerIsAnArrayReturnsArrayOfEachReflectionClass(): void
    {
        // Arrange
        $handler = [Example\TokenMiddleware::class, Example\ProtectedHandler::class];

        // Act
        $reflectors = $this->resolve($handler);

        // Assert
        $this->assertCount(2, $reflectors);
        $this->assertEquals($reflectors[0]->getName(), Example\TokenMiddleware::class);
        $this->assertEquals($reflectors[1]->getName(), Example\ProtectedHandler::class);
    }

    // -------------------------------------------------------------------------

    /** @return ReflectionClass[] */
    private function resolve(mixed $handler): array
    {
        $resolver = new ReflectionResolver();
        return $resolver->getReflections($handler);
    }
}
