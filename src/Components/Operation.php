<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Operation
{
    /** string[] */
    public array $tags = [];

    public ?string $summary = null;

    public ?string $description = null;

    public ?string $operationId = null;

    public ?Operation $get = null;

    /** @var Response[] */
    public array $responses = [];

    public bool $deprecated = false;
}
