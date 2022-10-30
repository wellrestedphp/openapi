<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use JsonSerializable;

class Path implements JsonSerializable
{
    public ?string $summary = null;

    public ?string $description = null;

    public ?Operation $get = null;

    public function jsonSerialize(): mixed
    {
        $json = [];
        if ($this->get) {
            $json['get'] = $this->get;
        }
        return (object) $json;
    }
}
