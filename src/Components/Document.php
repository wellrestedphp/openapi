<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Document
{
    public string $openapi = '3.0.3';

    public Info $info;

    /** @var array<string, Path> */
    public array $paths = [];

    public function __construct()
    {
        $this->info = new Info();
    }
}
