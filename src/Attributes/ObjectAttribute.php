<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;

class ObjectAttribute extends Attribute
{
    protected $cast = "object";

    protected function rules()
    {
        return 'object';
    }

    public function factory(Generator $faker)
    {
        return new \stdClass();
    }
}
