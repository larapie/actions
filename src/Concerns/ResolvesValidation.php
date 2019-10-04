<?php

namespace Larapie\Actions\Concerns;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Larapie\Actions\Attribute;

trait ResolvesValidation
{
    protected $errorBag = 'default';

    /**
     * @var Validator
     */
    protected $validator;

    public function validate($rules = [], $messages = [], $customAttributes = [])
    {
        return app(ValidationFactory::class)
            ->make($this->validationData(), $rules, $messages, $customAttributes)
            ->validate();
    }

    public function parentRules()
    {
        return [];
    }

    public function buildRules()
    {
        $rules = [];
        foreach (array_merge($this->parentRules(), $this->rules()) as $key => $rule) {
            if ($rule instanceof Attribute) {
                $rule = $rule->getRules();
            }
            $rules[$key] = $rule;
        }

        foreach ($this->includes() as $key => $included) {
            if (! array_key_exists($key, $rules)) {
                $rules[$key] = 'required';
            }
        }

        return $rules;
    }

    public function passesValidation()
    {
        return $this->getValidatorInstance()->passes();
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    public function validated(bool $recursive = true)
    {
        $data = $this->validator->validated();

        return array_merge($recursive ? $this->filterRulesRecursively($data) : $data, $this->includes());
    }

    protected function filterRulesRecursively(array $data)
    {
        //Dot notation makes it possible to parse nested values without recursion
        $original = Arr::dot($data);

        $filtered = [];
        $rules = collect($this->buildRules());
        $keys = $rules->keys();
        $rules->each(function ($rules, $key) use ($original, $keys, &$filtered) {
            //Allow for array or pipe-delimited rule-sets
            if (is_string($rules)) {
                $rules = explode('|', $rules);
            }
            //In case a rule requires an element to be an array, look for nested rules
            $nestedRules = $keys->filter(function ($otherKey) use ($key) {
                return strpos($otherKey, "$key.") === 0;
            });
            //If the input must be an array, default missing nested rules to a wildcard
            if (in_array('array', $rules) && $nestedRules->isEmpty()) {
                $key .= '.*';
            }

            foreach ($original as $dotIndex => $element) {
                //fnmatch respects wildcard asterisks
                if (fnmatch($key, $dotIndex)) {
                    //array_set respects dot-notation, building out a normal array
                    Arr::set($filtered, $dotIndex, $element);
                }
            }
        });

        return $filtered;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }

    protected function resolveValidation()
    {
        if (! $this->passesValidation()) {
            $this->failedValidation();
        }

        return $this;
    }

    protected function getValidatorInstance()
    {
        if ($this->validator) {
            return $this->validator;
        }

        $factory = app(ValidationFactory::class);

        $validator = method_exists($this, 'validator')
            ? $this->validator($factory)
            : $this->createDefaultValidator($factory);

        if (method_exists($this, 'withValidator')) {
            $this->resolveAndCall($this, 'withValidator', compact('validator'));
        }

        if (method_exists($this, 'afterValidator')) {
            $validator->after(function ($validator) {
                $this->resolveAndCall($this, 'afterValidator', compact('validator'));
            });
        }

        $this->setValidator($validator);

        return $this->validator;
    }

    protected function createDefaultValidator(ValidationFactory $factory)
    {
        return $factory->make(
            $this->validationData(), $this->buildRules(),
            $this->messages(), $this->attributes()
        );
    }

    protected function failedValidation()
    {
        throw (new ValidationException($this->validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    protected function getRedirectUrl()
    {
        return redirect()->getUrlGenerator()->previous();
    }

    protected function validationData()
    {
        return $this->all();
    }
}
