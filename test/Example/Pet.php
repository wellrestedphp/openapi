<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Example;

use JsonSerializable;

abstract class Pet implements JsonSerializable
{
    public function __construct(
        public readonly string $name
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
           'name' => $this->name
        ];
    }
}
