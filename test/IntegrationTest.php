<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\Document;
use WellRESTed\OpenAPI\DocumentGenerator;
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
}
