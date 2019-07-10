<?php

namespace Larapie\Actions\Attributes;

use Larapie\Actions\Attribute;

class StringAttribute extends Attribute
{
    protected function rules()
    {
        return 'string';
    }

    public function cast($value)
    {
        return (string) $value;
    }
}
