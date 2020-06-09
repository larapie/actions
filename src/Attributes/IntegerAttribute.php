<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\Rules\IntegerRules;

class IntegerAttribute extends Attribute
{
    use IntegerRules;

    protected $cast = 'int';

    protected function rules()
    {
        return 'integer';
    }

    public function factory(Generator $faker)
    {
        return $faker->numberBetween(0, 1000);
    }

}
