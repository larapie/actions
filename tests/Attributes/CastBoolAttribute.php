<?php

namespace Larapie\Actions\Tests\Attributes;

use Larapie\Actions\Attribute;

class CastBoolAttribute extends Attribute
{
    protected function rules()
    {
        return 'bool';
    }

    public function cast($value)
    {
        return (bool) $value;
    }
}