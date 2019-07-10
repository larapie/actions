<?php

namespace Larapie\Actions\Concerns;

use Larapie\Actions\Attribute;

trait ResolveIncludes
{
    public function includes()
    {
        return [];
    }

    protected function resolveIncludes()
    {
        $this->resolveAttributeIncludes();
    }

    protected function resolveAttributeIncludes()
    {
        $this->fill(
            array_merge($this->attributes, $this->includes())
        );
    }
}
