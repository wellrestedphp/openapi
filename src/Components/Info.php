<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use JsonSerializable;

class Info implements JsonSerializable
{
    public string $title = '';

    public string $version = '1.0.0';

    public function jsonSerialize(): mixed
    {
        $json = [
            'title' => $this->title,
            'version' => $this->version
        ];
        return (object) $json;
    }
}
