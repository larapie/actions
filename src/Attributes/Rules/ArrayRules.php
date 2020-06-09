<?php


namespace Larapie\Actions\Attributes\Rules;


trait ArrayRules
{
    public function size(int $size)
    {
        return $this->rule("size:$size");
    }

    public function min(int $length)
    {
        return $this->rule("min:$length");
    }

    public function max(int $length)
    {
        return $this->rule("max:$length");
    }
}