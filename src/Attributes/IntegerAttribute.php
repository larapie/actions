<?php


namespace Larapie\Actions\Attributes;


use Larapie\Actions\Attribute;

class IntegerAttribute extends Attribute
{
    protected function rules()
    {
        return 'integer';
    }

    public function cast($value)
    {
        return (int) $value;
    }
}