<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use WellRESTed\OpenAPI\Components\Document;
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

    public function encodeDocument(Server $server): Document
    {
        return $this->documentEncoder->encode($server);
    }

    public function documentToArray(Document $document): array
    {
        return $this->primativeEncoder->encode($document);
    }
}
