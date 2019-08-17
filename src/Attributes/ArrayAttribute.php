<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;

class ArrayAttribute extends Attribute
{
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
