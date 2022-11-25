<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Response
{
    public readonly string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }
}
