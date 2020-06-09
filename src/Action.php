<?php

namespace Larapie\Actions;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

abstract class Action extends Controller
{
    use Concerns\HasAttributes;
    use Concerns\ResolvesMethodDependencies;
    use Concerns\ResolvesAuthorization;
    use Concerns\ResolvesValidation;
    use Concerns\ResolveIncludes;
    use Concerns\ResolveDefaults;
    use Concerns\ResolveCasting;
    use Concerns\RunsAsController;
    use Concerns\SuccessHook;
    use Concerns\FailHook;

    protected $actingAs;
    protected $runningAs = 'object';

    protected $runAuthorized = true;

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

        $this->actingAs($action->user());

        return $this->run();
    }

    /**
     * @param bool $state
     * @return static
     */
    protected function setRunUnauthorized(bool $state)
    {
        $this->runAuthorized = $state;
        return $this;
    }

    /**
     * @return static
     */
    public function bypassAuthorization()
    {
        return $this->setRunUnauthorized(false);
    }

    /**
     * @return static
     */
    public function enableAuthorization()
    {
        return $this->setRunUnauthorized(true);
    }

    public function run(array $attributes = [])
    {
        $this->fill($attributes);
        $this->resolveIncludes();
        $this->resolveBeforeHook();

        if ($this->runAuthorized) {
            $this->resolveAuthorization();
        }

        $this->setValidatorInstance(null);
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
        $method = 'as' . Str::studly($this->runningAs);

        if (method_exists($this, $method)) {
            return $this->resolveAndCall($this, $method);
        }
    }

    public function runningAs($matches)
    {
        return in_array($this->runningAs, is_array($matches) ? $matches : func_get_args());
    }

    /**
     * @return static
     */
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

    public static function make(array $attributes = [])
    {
        return new static($attributes);
    }

    public static function execute(array $attributes = [])
    {
        return self::make()->run($attributes);
    }


}
