<?php

namespace Larapie\Actions\Concerns;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

trait ResolvesAuthorization
{
    protected function resolveAuthorization()
    {
        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        return $this;
    }

    public function passesAuthorization()
    {
        if (method_exists($this, 'authorize')) {
            return $this->resolveAndCall($this, 'authorize');
        }

        return true;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('This action is unauthorized.');
    }

    protected function can($ability, $arguments = [])
    {
        return Gate::forUser($this->user())->allows($ability, $arguments);
    }
}
