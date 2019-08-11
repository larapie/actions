<?php

namespace Larapie\Actions\Concerns;

use Illuminate\Support\Collection;
use Larapie\Actions\Attribute;

trait ResolveCasting
{
    protected function resolveAttributeCasting(array $data)
    {
        $attributes = (new Collection($this->rules()))
            ->filter(function ($rule) {
                return $rule instanceof Attribute;
            });

        return $attributes->intersectByKeys($data)->map(function (Attribute $attribute, $key) use ($data) {
            return $this->processCasting($attribute, $data[$key]);
        })->toArray();
    }

    private function processCasting(Attribute $attribute, $value)
    {
        if (($castFunction = $attribute->getCast()) !== null && is_callable($castFunction)) {
            return $castFunction($value);
        }

        if ((($type = $attribute->getCast()) !== null && is_string($type)) ||
            (is_string($type = $attribute->cast(null)) && in_array(strtolower($type), ['bool', 'boolean', 'string', 'double', 'float', 'int', 'integer', 'array', 'object']))) {
            return $this->castFromString($type, $value);
        }

        if (! ($attribute->cast(null) instanceof Attribute)) {
            return $attribute->cast($value);
        }

        return $value;
    }

    private function castFromString(string $type, $value)
    {
        switch (strtolower($type)) {
            case 'boolean':
            case 'bool':
                return (bool) $value;
            case 'string':
                return (string) $value;
            case 'double':
                return (float) $value;
            case 'integer':
            case 'int':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'array':
                return (array) $value;
            case 'object':
                return (object) $value;
            default:
                throw new \RuntimeException('cast type not supported');
        }
    }
}
