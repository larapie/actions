<?php

namespace Larapie\Actions\Attributes;

use Faker\Generator;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\Rules\FileRules;

class FileAttribute extends Attribute
{
    use FileRules;

    protected function rules()
    {
        return 'file';
    }
}
