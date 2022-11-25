<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Path
{
    public ?string $summary = null;

    public ?string $description = null;

    public ?Operation $get = null;
}
