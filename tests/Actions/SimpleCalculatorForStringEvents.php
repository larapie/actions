<?php

namespace Larapie\Actions\Tests\Actions;

class SimpleCalculatorForStringEvents extends SimpleCalculator
{
    public function getAttributesFromEvent($operation, $left, $right)
    {
        return compact('operation', 'left', 'right');
    }
}