<?php


namespace Larapie\Actions\Attributes\Rules;


trait IntegerRules
{
    public function min(int $value)
    {
        return $this->rule("min:$value");
    }

    public function max(int $value)
    {
        return $this->rule("max:$value");
    }
}