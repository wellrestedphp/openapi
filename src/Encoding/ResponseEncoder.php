<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use WellRESTed\OpenAPI\Components\Response;
use WellRESTed\OpenAPI\Components\StatusCode;

class ResponseEncoder
{
    private ReflectionResolver $reflectionResolver;

    public function __construct(ReflectionResolver $reflectionResolver)
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    public function encode(mixed $handler): array
    {
        $responses = [];
        $reflections = $this->reflectionResolver->getReflections($handler);

        foreach ($reflections as $reflection) {
            $atts = $reflection->getAttributes(StatusCode::class);
            foreach ($atts as $att) {
                $statusCode = $att->newInstance();
                $responses[$statusCode->code] = new Response($statusCode->description);
            }
        }

        return $responses;
    }
}
