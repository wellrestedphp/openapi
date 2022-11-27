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

        $docGen = new DocumentGenerator();
        $this->doc = $docGen->generate($server);
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

    public function testShouldProvideParameterFromPathTemplate(): void
    {
        $params = $this->doc->paths['/cats/{id}']->get->parameters;
        $map = [];
        foreach ($params as $param) {
            $map[$param->name] = $param;
        }
        $this->assertArrayHasKey('id', $map);
    }
}
