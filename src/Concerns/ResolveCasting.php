<?php

namespace Larapie\Actions\Concerns;

use Illuminate\Support\Collection;
use Larapie\Actions\Attribute;

trait ResolveCasting
{
    protected function resolveCasting()
    {
        $this->resolveAttributeCasting();
    }

    protected function resolveAttributeCasting()
    {
        $attributes = new Collection($this->rules());
        $attributes = (new Collection($this->rules()))->filter(function ($rule) {
            return $rule instanceof Attribute;
        });

        $data = $this->validated();

        $castedValues = $attributes->intersectByKeys($this->validated())->map(function (Attribute $value, $key) {
            return $value->cast($this->validated()[$key]);
        });

        $test = collect($this->validated())->merge($attributes)->toArray();

        return $test;
    }
}
