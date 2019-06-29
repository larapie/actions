<?php

namespace Larapie\Actions;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

abstract class Action extends Controller
{
    use Concerns\SerializesModels;
    use Concerns\HasAttributes;
    use Concerns\ResolvesMethodDependencies;
    use Concerns\ResolvesAuthorization;
    use Concerns\ResolvesValidation;
    use Concerns\RunsAsController;
    use Concerns\SuccessHook;
    use Concerns\FailHook;

    protected $actingAs;
    protected $runningAs = 'object';

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);

        if (method_exists($this, 'register')) {
            $this->register();
        }
    }

    public static function createFrom(self $action)
    {
        return (new static())->fill($action->all());
    }

    public function runAs(self $action)
    {
        if ($action->runningAs('controller')) {
            return $this->runAsController($action->getRequest());
        }

        return $this->run();
    }

    public function run(array $attributes = [])
    {
        $this->fill($attributes);
        $this->resolveBeforeHook();
        $this->resolveAuthorization();
        $this->resolveValidation();

        try {
            $value = $this->resolveAndCall($this, 'handle');
        } catch (Throwable $exception) {
            $this->failHook($exception);
        }

        return tap($value, function ($value) {
            $this->successHook($value);
        });
    }

    public function resolveBeforeHook()
    {
        $method = 'as'.Str::studly($this->runningAs);

        if (method_exists($this, $method)) {
            return $this->resolveAndCall($this, $method);
        }
    }

    public function runningAs($matches)
    {
        return in_array($this->runningAs, is_array($matches) ? $matches : func_get_args());
    }

    public function actingAs($user)
    {
        $this->actingAs = $user;

        return $this;
    }

    public function user()
    {
        return $this->actingAs ?? Auth::user();
    }

    public function reset($user = null)
    {
        $this->actingAs = $user;
        $this->attributes = [];
        $this->validator = null;
    }

    public function delegateTo($actionClass)
    {
        return $actionClass::createFrom($this)->runAs($this);
    }
}
