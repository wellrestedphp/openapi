<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use JsonSerializable;

class Document implements JsonSerializable
{
    public string $openapi = '3.0.3';

    public Info $info;

    /** @var array<string, Path> */
    public array $paths = [];

    public function __construct()
    {
        $this->info = new Info();
    }

    public function jsonSerialize(): object
    {
        $json = [
            'openapi' => $this->openapi,
            'info' => $this->info,
            'paths' => $this->paths
        ];
        return (object) $json;
    }
}


