<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Encoding;

use ReflectionObject;
use ReflectionProperty;
use stdClass;

/**
 * Recursively converts an object or array to primatives.
 */
class PrimativeEncoder
{
    public function encode(object|array $source): stdClass|array
    {
        if (is_array($source)) {
            return $this->encodeArray($source);
        }

        $encoded = new stdClass();

        $reflection = new ReflectionObject($source);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $encodedValue = $this->encodedProperty($source, $property);
            if ($encodedValue !== null) {
                $key = $property->getName();
                $encoded->{$key} = $encodedValue;
            }
        }

        return $encoded;
    }

    private function encodedProperty(object $source, ReflectionProperty $property): mixed
    {
        if (!$property->isInitialized($source)) {
            return null;
        }

        $key = $property->getName();
        $value = $property->getValue($source);

        if ($property->isDefault()) {
            $reflectionProp = new ReflectionProperty($source, $key);
            if ($reflectionProp->getAttributes(OmitDefault::class)) {
                return null;
            }
        }

        if ($value === null) {
            return null;
        } elseif (is_array($value)) {
            return $this->encodeArray($value);
        } elseif (is_object($value)) {
            return $this->encodeObject($value);
        } else {
            return $value;
        }
        return null;
    }

    private function encodeArray(array $source): array
    {
        $encoded = [];
        foreach ($source as $key => $value) {
            $encoded[$key] = $this->encode($value);
        }
        return $encoded;
    }

    private function encodeObject(object $source): mixed
    {
        if (enum_exists($source::class)) {
            return $source->value;
        } else {
            return $this->encode($source);
        }
    }
}
