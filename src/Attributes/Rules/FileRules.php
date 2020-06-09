<?php


namespace Larapie\Actions\Attributes\Rules;


trait FileRules
{
    public function image()
    {
        return $this->rule('image');
    }
}