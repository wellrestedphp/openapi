<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Operation extends Component
{
    /** string[] */
    public array $tags = [];

    public ?string $summary = null;

    public ?string $description = null;

    public ?string $operationId = null;

    /** @var Response[] */
    public array $responses = [];

    /** @var Parameter[] */
    public array $parameters = [];

    #[JsonOmitDefault]
    public bool $deprecated = false;
}
