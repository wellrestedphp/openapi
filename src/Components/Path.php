<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

class Path extends Component
{
    public ?string $summary = null;

    public ?string $description = null;

    public ?Operation $get = null;

    public ?Operation $put = null;

    public ?Operation $post = null;

    public ?Operation $delete = null;

    public ?Operation $options = null;

    public ?Operation $head = null;

    public ?Operation $patch = null;

    public ?Operation $trace = null;
}
