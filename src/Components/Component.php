<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use JsonSerializable;
use ReflectionObject;
use ReflectionProperty;

abstract class Component implements JsonSerializable
{
    public function jsonSerialize(): mixed
    {
        $result = [];

        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            if (!$property->isInitialized($this)) {
                continue;
            }

            $key = $property->getName();
            $value = $property->getValue($this);

            if ($value === null) {
                continue;
            }

            $result[$key] = $value;
        }
        return $result;
    }
}
