<?php


namespace Larapie\Actions\Attributes;


use Larapie\Actions\Attribute;

class BooleanAttribute extends Attribute
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