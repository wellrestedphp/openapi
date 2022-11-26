<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use WellRESTed\Test\TestCase;

class ComponentTest extends TestCase
{
    public function testDoesNotSerializesNullProperties(): void
    {
        // Arrange
        $myComponent = new class () extends Component {
            public string $name;
            public ?string $description;
            public ?string $summary;
        };
        $myComponent->name = 'Name';
        $myComponent->description = 'Description';
        $myComponent->summary = null;

        // Act
        $decoded = json_decode(json_encode($myComponent), true);

        // Assert
        $this->assertEquals('Name', $decoded['name']);
        $this->assertEquals('Description', $decoded['description']);
        $this->assertArrayNotHasKey('summary', $decoded);
    }

    public function testDoesNotSerializesUnintializedProperties(): void
    {
        // Arrange
        $myComponent = new class () extends Component {
            public string $name;
            public string $description;
            public ?string $summary;
        };
        $myComponent->name = 'Name';

        // Act
        $decoded = json_decode(json_encode($myComponent), true);

        // Assert
        $this->assertEquals('Name', $decoded['name']);
        $this->assertArrayNotHasKey('description', $decoded);
        $this->assertArrayNotHasKey('summary', $decoded);
    }
}
