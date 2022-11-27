<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

enum In: string
{
    case QUERY = 'query';
    case PATH = 'path';
    case HEADER = 'header';
    case COOKIE = 'cookie';
}
