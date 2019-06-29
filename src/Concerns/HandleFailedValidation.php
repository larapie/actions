<?php

namespace Larapie\Actions\Concerns;

use Larapie\Actions\Exception\ValidationException;

trait HandleFailedValidation
{
    protected function failedValidation()
    {
        throw new ValidationException($this->validator);
    }
}