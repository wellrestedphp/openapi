<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS|Attribute::IS_REPEATABLE)]
class StatusCode
{
    public readonly string $code;

    public readonly string $description;

    public function __construct(
        string|int $code,
        string $description = ''
    ) {
        $this->code = (string) $code;
        $this->description = $description;
    }
}
