<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;

class DoubleAttribute extends FloatAttribute
{
    protected $cast = 'double';
}
