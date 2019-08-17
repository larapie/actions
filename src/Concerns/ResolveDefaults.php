<?php

namespace Larapie\Actions\Concerns;

use Larapie\Actions\Attribute;

trait ResolveDefaults
{
    public function default()
    {
        return [];
    }

    protected function resolveDefaults()
    {
        return array_merge($this->resolveClassDefaults(), $this->resolveAttributeDefaults());
    }

    protected function resolveClassDefaults()
    {
        return $this->default();
    }

    protected function resolveAttributeDefaults()
    {
        $defaults = collect($this->rules())
            ->filter(function ($rule, $key) {
                return $rule instanceof Attribute && $rule->hasDefault();
            })
            ->map(function (Attribute $attribute) {
                return $attribute->getDefault();
            })
            ->toArray();

        return $defaults;
    }
}
