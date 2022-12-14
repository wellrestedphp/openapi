<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\Document;
use WellRESTed\OpenAPI\Example\ContainerBuilder;
use WellRESTed\Server;
use WellRESTed\Test\TestCase;

class IntegrationTest extends TestCase
{
    private Document $doc;

    public function setUp(): void
    {
        parent::setUp();

        $builder = new ContainerBuilder();
        $container = $builder->build();

        $server = $container->get(Server::class);

        $encoder = new OpenAPIEncoder();
        $this->doc = $encoder->encodeDocument($server);
    }

    // -------------------------------------------------------------------------

    public function testProvidesPathsForStaticEndpoints(): void
    {
        $paths = $this->doc->paths;
        $this->assertGreaterThan(0, count($paths));
        $this->assertArrayHasKey('/cats/', $paths);
        $this->assertArrayHasKey('/dogs/', $paths);
    }

    public function testProvidesMethodsSupportedByEndpoints(): void
    {
        $operations = $this->doc->paths['/cats/'];
        $this->assertNotNull($operations->get);
        $this->assertNotNull($operations->post);
    }

    public function testProvidesParametersFromAttributes(): void
    {
        $params = $this->doc->paths['/cats/']->get->parameters;
        $map = [];
        foreach ($params as $param) {
            $map[$param->name] = $param;
        }
        $this->assertArrayHasKey('color', $map);
    }

    public function testProvidesParameterFromPathTemplate(): void
    {
        $params = $this->doc->paths['/cats/{id}']->get->parameters;
        $map = [];
        foreach ($params as $param) {
            $map[$param->name] = $param;
        }
        $this->assertArrayHasKey('id', $map);
    }

    public function testReadsAttributesFromMiddlewareQueue(): void
    {
        $responses = $this->doc->paths['/protected/']->get->responses;
        $this->assertArrayHasKey('200', $responses);
        $this->assertArrayHasKey('401', $responses);
        $this->assertArrayHasKey('403', $responses);
    }

    public function testReadsAttributesFromServiceName(): void
    {
        $params = $this->doc->paths['/dogs/']->get->parameters;
        $map = [];
        foreach ($params as $param) {
            $map[$param->name] = $param;
        }
        $this->assertArrayHasKey('breed', $map);
    }
}
