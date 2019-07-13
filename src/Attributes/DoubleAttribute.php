<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;

class DoubleAttribute extends Attribute
{
    protected function rules()
    {
        return 'numeric';
    }

    public function cast($value)
    {
        return (float) $value;
    }

    public function factory(Generator $faker)
    {
        return $faker->randomFloat(2, 0, 1000);
    }
}
