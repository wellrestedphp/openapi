<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\Document;
use WellRESTed\OpenAPI\Components\OpenAPI;
use WellRESTed\OpenAPI\Encoding\DocumentEncoder;
use WellRESTed\OpenAPI\Encoding\PrimativeEncoder;
use WellRESTed\Server;

class OpenAPIEncoder
{
    private DocumentEncoder $documentEncoder;
    private PrimativeEncoder $primativeEncoder;

    public function __construct()
    {
        $this->documentEncoder = new DocumentEncoder();
        $this->primativeEncoder = new PrimativeEncoder();
    }

    public function encode(Server $server): OpenAPI
    {
        return $this->documentEncoder->encode($server);
    }

    public function documentToArray(OpenAPI $document): array
    {
        return $this->primativeEncoder->encode($document);
    }
}
