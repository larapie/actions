<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\Rules\ObjectRules;

class ObjectAttribute extends Attribute
{
    use ObjectRules;

    protected $cast = 'object';

    protected function rules()
    {
        return 'object';
    }

    public function factory(Generator $faker)
    {
        return new \stdClass();
    }
}
