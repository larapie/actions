<?php

namespace Larapie\Actions\Concerns;

trait SuccessHook
{
    /* Extensive resolving to ensure you always get the proper object as parameter */
    protected function successHook($result)
    {
        if (method_exists($this, 'onSuccess')) {
            try {
                $parameters = (new \ReflectionMethod($this, 'onSuccess'))->getParameters();
                $extraParameter = $this->resolveParameter($parameters, $result);
            } catch (\ReflectionException $e) {
            }
            $this->resolveAndCall($this, 'onSuccess', $extraParameter ?? []);
        }
    }

    private function resolveParameter($parameters, $result)
    {
        $extraParameter = null;

        //BIND the value to the first parameter with the correct type
        collect($parameters)
            ->filter(function (\ReflectionParameter $parameter) {
                return $parameter->hasType();
            })
            ->filter(function (\ReflectionParameter $parameter) use ($result) {
                return (gettype($result) === 'object' ? get_class($result) : gettype($result)) === $parameter->getType()->getName();
            })
            ->each(function (\ReflectionParameter $parameter) use ($result, &$extraParameter) {
                $extraParameter = [$parameter->getName() => $result];

                return false;
            });

        if (isset($extraParameter)) {
            return $extraParameter;
        }

        //BIND the value to the parameter with the same name
        //IF there's not a parameter with the same name as the value type
        //CHOOSE the first value that doesn't have a type
        collect($parameters)
            ->filter(function (\ReflectionParameter $parameter) {
                return !$parameter->hasType();
            })
            ->each(function (\ReflectionParameter $parameter) use ($result, &$extraParameter) {
                if ($extraParameter === null) {
                    $extraParameter = [$parameter->getName() => $result];
                } elseif (strcasecmp(substr(strrchr(get_class($result), '\\'), 1), $parameter->getName()) == 0) {
                    $extraParameter = [$parameter->getName() => $result];

                    return false;
                }
            });

        return $extraParameter;
    }
}
