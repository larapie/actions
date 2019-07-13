<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
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

    public function factory(Generator $faker)
    {
        return $faker->numberBetween(0, 1000);
    }
}
