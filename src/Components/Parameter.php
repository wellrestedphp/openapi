<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Parameter extends Component
{
    public readonly string $name;

    public readonly In $in;

    public function __construct(
        string $name,
        In $in
    ) {
        $this->name = $name;
        $this->in = $in;
    }
}
