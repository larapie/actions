<?php


namespace Larapie\Actions\Tests\Attributes;


use Larapie\Actions\Attribute;

class RequiredAttribute extends Attribute
{
    protected function rules()
    {
        return 'required';
    }
}