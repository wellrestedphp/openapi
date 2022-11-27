<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Parameter extends Component
{
    public readonly string $name;

    public readonly In $in;

    public string $description = '';

    public bool $deprecated = false;

    public bool $required;

    public function __construct(
        string $name,
        In $in = In::QUERY,
        string $description = '',
        bool $deprecated = false
    ) {
        $this->name = $name;
        $this->in = $in;
        $this->description = $description;
        $this->deprecated = $deprecated;
        $this->required = ($in === In::PATH);
    }
}
