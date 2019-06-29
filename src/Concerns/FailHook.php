<?php

namespace Larapie\Actions\Concerns;

use Throwable;

trait FailHook
{
    protected function failHook(Throwable $exception)
    {
        if (! method_exists($this, 'onFail')) {
            throw $exception;
        }
        $this->resolveAndCall($this, 'onFail', compact('exception'));

        throw $exception;
    }
}
