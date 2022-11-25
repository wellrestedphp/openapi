<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI;

use ReflectionClass;
use WellRESTed\OpenAPI\Attributes\StatusCode;
use WellRESTed\OpenAPI\Components\Response;

class ResponseGenerator
{
    public function generate(mixed $handler): array
    {
        $reflection = new ReflectionClass($handler);

        $responses = [];

        $atts = $reflection->getAttributes(StatusCode::class);
        foreach ($atts as $att) {
            $statusCode = $att->newInstance();
            $responses[$statusCode->code] = new Response($statusCode->description);
        }

        return $responses;
    }
}