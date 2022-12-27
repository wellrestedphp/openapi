<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use WellRESTed\OpenAPI\Components\In;
use WellRESTed\OpenAPI\Components\Operation;
use WellRESTed\OpenAPI\Components\Parameter;
use WellRESTed\OpenAPI\Components\Path;
use WellRESTed\Test\TestCase;

class PrimativeEncoderTest extends TestCase
{
    private PrimativeEncoder $encoder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->encoder = new PrimativeEncoder();
    }

    // -------------------------------------------------------------------------

    public function testEncodesPropertiesToAssociativeArray(): void
    {
        // Arrange
        $source = new class () {
            public string $name;
            public ?string $description;
            public ?string $summary;
        };
        $source->name = 'Name';
        $source->description = 'Description';
        $source->summary = 'Summary';

        // Act
        $encoded = $this->encoder->encode($source);

        // Assert
        $this->assertIsArray($encoded);
        $this->assertEquals('Name', $encoded['name']);
        $this->assertEquals('Description', $encoded['description']);
        $this->assertEquals('Summary', $encoded['summary']);
    }

    public function testDoesNotEncodeNullProperties(): void
    {
        // Arrange
        $source = new class () {
            public string $name;
            public ?string $description;
            public ?string $summary;
        };
        $source->name = 'Name';
        $source->description = 'Description';
        $source->summary = null;

        // Act
        $encoded = $this->encoder->encode($source);

        // Assert
        $this->assertArrayNotHasKey('summary', $encoded);
    }

    public function testDoesNotEncodeUnintializedProperties(): void
    {
        // Arrange
        $source = new class () {
            public string $name;
            public string $description;
        };
        $source->name = 'Name';

        // Act
        $encoded = $this->encoder->encode($source);

        // Assert
        $this->assertArrayNotHasKey('description', $encoded);
    }

    public function testWhenPropertyHasDefaultValueAndOmitDefaultDoesNotEncode(): void
    {
        // Arrange
        $source = new class () {
            #[OmitDefault]
            public bool $deprecated = false;
        };

        // Act
        $encoded = $this->encoder->encode($source);

        // Assert
        $this->assertArrayNotHasKey('deprecated', $encoded);
    }

    public function testEncodesBackedEnumsToScalars(): void
    {
        // Arrange
        $param = new Parameter('foo', In::QUERY);

        // Act
        $encoded = $this->encoder->encode($param);

        // Assert
        $this->assertIsString($encoded['in']);
    }

    public function testEncodesObjectFieldToNestedAssociateArray(): void
    {
        // Arrange
        $path = new Path();
        $path->get = new Operation();
        $path->get->summary = 'Summary';
        $path->get->description = 'Description';

        // Act
        $encoded = $this->encoder->encode($path);

        // Assert
        $this->assertIsArray($encoded);
        $this->assertIsArray($encoded['get']);
        $this->assertEquals('Summary', $encoded['get']['summary']);
        $this->assertEquals('Description', $encoded['get']['description']);
    }

    public function testEncodesArrayFieldToArray(): void
    {
        // Arrange
        $operation = new Operation();
        $operation->parameters[] = new Parameter('foo', In::PATH);
        $operation->parameters[] = new Parameter('bar', In::QUERY);

        // Act
        $encoded = $this->encoder->encode($operation);

        // Assert
        $this->assertIsArray($encoded);
        $this->assertIsArray($encoded['parameters']);
        $this->assertIsArray($encoded['parameters'][0]);
        $this->assertIsArray($encoded['parameters'][1]);
    }

    public function testEncodesArrayToArray(): void
    {
        // Arrange
        $params = [];
        $params[] = new Parameter('foo', In::PATH);
        $params[] = new Parameter('bar', In::QUERY);

        // Act
        $encoded = $this->encoder->encode($params);

        // Assert
        $this->assertIsArray($encoded);
        $this->assertIsArray($encoded[0]);
        $this->assertIsArray($encoded[1]);
    }
}
