<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;

class FloatAttribute extends Attribute
{
    protected $cast = "float";

    protected function rules()
    {
        return 'numeric';
    }

    public function factory(Generator $faker)
    {
        return $faker->randomFloat(2, 0, 1000);
    }
}
