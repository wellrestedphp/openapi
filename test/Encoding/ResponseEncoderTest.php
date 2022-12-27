<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use WellRESTed\OpenAPI\Components\StatusCode;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class ResponseEncoderTest extends TestCase
{
    private Server $server;
    private ResponseEncoder $encoder;

    public function setUp(): void
    {
        parent::setUp();

        $this->server = new Server();
        $resolver = new Reflectionresolver($this->server);
        $this->encoder = new ResponseEncoder($resolver);
    }

    public function testProvidesResponsesDescribedByStatusCodeAttributes(): void
    {
        // Arrange
        $handler = new #[StatusCode(200)] #[StatusCode(404)] class {};

        // Act
        $responses = $this->encoder->encode($handler);

        // Assert
        $this->assertArrayHasKey('200', $responses);
        $this->assertArrayHasKey('404', $responses);
    }
}
