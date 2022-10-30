<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use JsonSerializable;

class Operation implements JsonSerializable
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

    public function jsonSerialize(): mixed
    {
        $json = [
        ];
        return (object) $json;
    }
}
