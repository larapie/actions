<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
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

    public function factory(Generator $faker)
    {
        return $faker->boolean;
    }
}
