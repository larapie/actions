<?php

namespace Larapie\Actions;

use Faker\Factory;
use Faker\Generator;

class Attribute
{
    protected $data = [];

    protected $cast = null;

    /**
     * Attribute constructor.
     */
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
        $attribute->rule('required');
        $attribute->setDefault($value);

        return $attribute;
    }

    public static function required()
    {
        return static::make()->rule('required');
    }

    public function cast($cast)
    {
        if (is_callable($cast) || is_string($cast))
            $this->cast = $cast;

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

    /**
     * @return mixed
     */
    public static function fake()
    {
        return static::make()->factory(Factory::create());
    }

    public function factory(Generator $faker)
    {
        throw new \RuntimeException('Faking this attribute is not supported. Is the factory method implemented?');
    }

    protected function setRules(array $rules)
    {
        $this->data['rules'] = $rules;
    }

    public function rule(...$rules)
    {
        foreach ($rules as $rule) {
            $this->setRules(
                collect($this->extractRules($rule))
                    ->flatten()
                    ->merge($this->data['rules'])
                    ->toArray()
            );
        }

        return $this;
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

    public function getPreProcessing(){
        return $this->preProcess;
    }

    public function getCast(){
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
