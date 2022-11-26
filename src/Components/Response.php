<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Response extends Component
{
    public readonly string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }
}
