<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class OpenAPI
{
    public string $openapi = '3.1.0';

    public Info $info;

    /** @var array<string, Path> */
    public array $paths = [];

    public function __construct()
    {
        $this->info = new Info();
    }
}
