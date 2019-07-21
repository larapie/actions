<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;

class StringAttribute extends Attribute
{
    protected $cast = "string";

    protected function rules()
    {
        return 'string';
    }

    public function factory(Generator $faker)
    {
        return $faker->text;
    }
}
