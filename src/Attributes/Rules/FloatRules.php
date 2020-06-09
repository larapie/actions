<?php


namespace Larapie\Actions\Attributes\Rules;


trait FloatRules
{
    public function min(float $value)
    {
        return $this->rule("min:$value");
    }

    public function max(float $value)
    {
        return $this->rule("max:$value");
    }

    public function lesserThan(float $value)
    {
        return $this->rule("lt:$value");
    }

    public function lesserOrEqualThan(float $value)
    {
        return $this->rule("lte:$value");
    }

    public function greaterThan(float $value)
    {
        return $this->rule("gt:$value");
    }

    public function greaterOrEqualThan(float $value)
    {
        return $this->rule("gte:$value");
    }
}