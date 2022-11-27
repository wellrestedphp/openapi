<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\StatusCode;
use WellRESTed\Test\TestCase;

class ResponseGeneratorTest extends TestCase
{
    public function testProvidesResponsesDescribedByStatusCodeAttributes(): void
    {
        // Arrange
        $handler = new #[StatusCode(200)] #[StatusCode(404)] class {};

        // Act
        $respGen = new ResponseGenerator();
        $responses = $respGen->generate($handler);

        // Assert
        $this->assertArrayHasKey('200', $responses);
        $this->assertArrayHasKey('404', $responses);
    }
}
