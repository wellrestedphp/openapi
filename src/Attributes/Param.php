<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Attributes;

use Attribute;
use WellRESTed\OpenAPI\Components\In;

#[Attribute(Attribute::TARGET_CLASS|Attribute::IS_REPEATABLE)]
class Param
{
    public readonly string $name;

    public readonly In $in;

    public function __construct(
        string $name,
        In $in = In::QUERY
    ) {
        $this->name = $name;
        $this->in = $in;
    }
}
