<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\StatusCode;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class ResponseGeneratorTest extends TestCase
{
    private Server $server;
    private ResponseGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $this->server = new Server();
        $resolver = new Reflectionresolver($this->server);
        $this->generator = new ResponseGenerator($resolver);
    }

    public function testProvidesResponsesDescribedByStatusCodeAttributes(): void
    {
        // Arrange
        $handler = new #[StatusCode(200)] #[StatusCode(404)] class {};

        // Act
        $responses = $this->generator->generate($handler);

        // Assert
        $this->assertArrayHasKey('200', $responses);
        $this->assertArrayHasKey('404', $responses);
    }
}
