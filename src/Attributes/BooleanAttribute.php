<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;

class BooleanAttribute extends Attribute
{
    protected $cast = 'bool';

    protected function rules()
    {
        return 'bool';
    }

    public function factory(Generator $faker)
    {
        return $faker->boolean;
    }
}
