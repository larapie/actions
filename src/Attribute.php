<?php

namespace Larapie\Actions;

use Faker\Factory;
use Faker\Generator;
use Larapie\Actions\Attributes\Rules\GeneralRules;

class Attribute
{
    use GeneralRules;

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

    /**
     * @return static
     */
    protected static function make()
    {
        return new static();
    }


    /**
     * @return static
     */
    public static function default($value)
    {
        $attribute = static::make();
        $attribute->setDefault($value);

        return $attribute;
    }

    /**
     * @return static
     */
    public static function required()
    {
        return static::make()->require();
    }

    /**
     * @return static
     */
    public function require()
    {
        return $this->rule('required');
    }

    /**
     * @return static
     */
    public function cast($cast)
    {
        if (is_callable($cast) || is_string($cast)) {
            $this->cast = $cast;
        }

        return $this;
    }

    /**
     * @return static
     */
    public static function optional()
    {
        return tap(static::make(), function (Attribute $attribute) {
            $attribute->setRules(
                collect($attribute->getRules())
                    ->reject(function ($value) {
                        return $value === 'required';
                    })
                    ->toArray()
            );
        });
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
        throw new \RuntimeException('The fake method for this attribute is not implemented.');
    }

    protected function setRules(array $rules)
    {
        $this->data['rules'] = $rules;
    }

    /**
     * @param mixed ...$rules
     * @return static
     */
    public function rule(...$rules)
    {
        collect($rules)->each(function ($rule) {
            $this->setRules(
                collect($this->extractRules($rule))
                    ->flatten()
                    ->merge($this->data['rules'])
                    ->unique()
                    ->toArray()
            );
        });

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

    public function getRules(): array
    {
        return $this->data['rules'];
    }

    public function getCast()
    {
        return $this->cast;
    }

    public function hasDefault(): bool
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

    public function __toString()
    {
        return implode('|', $this->getRules());
    }
}
