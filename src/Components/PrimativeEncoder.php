<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use ReflectionObject;
use ReflectionProperty;

/**
 * Recursively converts an object or array to primatives.
 */
class PrimativeEncoder
{
    public static function encode(object|array $source): array
    {
        if (is_array($source)) {
            return self::encodeArray($source);
        }

        $encoded = [];

        $reflection = new ReflectionObject($source);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $encodedValue = self::encodedProperty($source, $property);
            if ($encodedValue !== null) {
                $key = $property->getName();
                $encoded[$key] = $encodedValue;
            }
        }

        return $encoded;
    }

    private static function encodedProperty(object $source, ReflectionProperty $property): mixed
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
            return self::encodeArray($value);
        } elseif (is_object($value)) {
            return self::encodeObject($value);
        } else {
            return $value;
        }
        return null;
    }

    private static function encodeArray(array $source): array
    {
        $encoded = [];
        foreach ($source as $key => $value) {
            $encoded[$key] = self::encode($value);
        }
        return $encoded;
    }

    private static function encodeObject(object $source): mixed
    {
        if (enum_exists($source::class)) {
            return $source->value;
        } else {
            return self::encode($source);
        }
    }
}
