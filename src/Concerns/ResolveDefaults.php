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
        $this->resolveClassDefaults();
        $this->resolveAttributeDefaults();
    }

    protected function resolveClassDefaults()
    {
        $this->fill(
            array_merge($this->default(), $this->attributes)
        );
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

        $this->fill(
            array_merge($defaults, $this->attributes)
        );
    }
}