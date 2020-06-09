<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\Rules\ArrayRules;

class ArrayAttribute extends Attribute
{
    use ArrayRules;

    protected $cast = 'array';

    protected function rules()
    {
        return 'array';
    }

    public function factory(Generator $faker)
    {
        return [];
    }
}
