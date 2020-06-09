<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\Rules\StringRules;

class StringAttribute extends Attribute
{
    use StringRules;

    protected $cast = 'string';

    protected function rules()
    {
        return 'string';
    }

    public function factory(Generator $faker)
    {
        return $faker->text;
    }
}
