<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Server
{
    public function __construct(
        public string $url,
        public string $description = ''
    ) {
    }
}
