<?php

namespace Larapie\Actions;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Str;

class Attribute
{
    protected $data = [];

    protected $cast = null;

    final public function __construct()
    {
        $this->boot();
    }

    protected function boot()
    {
        $this->initializeRules();
    }

    protected function initializeRules()
    {
        if (method_exists($this, 'rules')) {
            $this->data['rules'] = $this->extractRules($this->rules());
        } else {
            $this->data['rules'] = [];
        }
    }

    protected static function make()
    {
        return new static();
    }

    public static function default($value)
    {
        $attribute = static::make();
        $attribute->setDefault($value);
        return $attribute;
    }

    public static function required()
    {
        return static::make()->require();
    }

    public function require()
    {
        $this->rule('required');
        return $this;
    }

    public function cast($cast)
    {
        if (is_callable($cast) || is_string($cast)) {
            $this->cast = $cast;
        }

        return $this;
    }

    public static function optional()
    {
        $attribute = static::make();

        $attribute->setRules(
            collect($attribute->getRules())
                ->reject(function ($value) {
                    return $value === 'required';
                })
                ->toArray()
        );

        return $attribute;
    }

    public static function fake()
    {
        return static::make()->factory(Factory::create());
    }

    public function factory(Generator $faker)
    {
        throw new \RuntimeException('The fake method for this attribute is not implemented.');
    }

    protected function setRules(array $rules)
    {
        $this->data['rules'] = $rules;
    }

    public function rule(...$rules)
    {
        collect($rules)->each(function ($rule) {
            $this->setRules(
                collect($this->extractRules($rule))
                    ->flatten()
                    ->merge($this->data['rules'])
                    ->toArray()
            );
        });

        return $this;
    }

    public function nullable()
    {
        $this->rule('nullable');

        return $this;
    }

    public function isNullable(): bool
    {
        return $this->hasRule('nullable');
    }

    public function hasRule(string $rule): bool
    {
        return in_array($rule, $this->getRules());
    }

    protected function extractRules($rules): array
    {
        if (is_array($rules)) {
            return $rules;
        } elseif (is_string($rules)) {
            return explode('|', $rules);
        } elseif (is_object($rules)) {
            return [$rules];
        }

        return [];
    }

    public function getRules()
    {
        return $this->data['rules'];
    }

    public function getCast()
    {
        return $this->cast;
    }

    public function hasDefault()
    {
        return array_key_exists('default', $this->data);
    }

    public function getDefault()
    {
        return $this->data['default'] ?? null;
    }

    protected function setDefault($value)
    {
        $this->data['default'] = $value;
    }
}
