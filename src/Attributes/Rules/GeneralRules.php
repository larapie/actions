<?php


namespace Larapie\Actions\Attributes\Rules;


use Illuminate\Validation\Rule;
use Larapie\Actions\Attribute;

trait GeneralRules
{
    /**
     * @return static
     */
    public function nullable()
    {
        return $this->rule('nullable');
    }

    /**
     * @param \Closure|bool $callback
     * @return GeneralRules
     */
    public function requireIf($callback)
    {
        if (is_callable($callback)) {
            return call_user_func($callback) ? $this->require() : $this;
        }

        return $callback ? $this->require() : $this;
    }

    public function requiredIfField(string $field, ...$values)
    {
        return $this->rule("required_if:$field, " . implode(',', $values));
    }

    public function requiredUnlessField(string $field, ...$values)
    {
        return $this->rule("required_unless:$field," . implode(',', $values));
    }


    public function requiredWith(string ...$fields)
    {
        return $this->rule("required_with:" . implode(',', $fields));
    }

    public function requiredWithAll(string ...$fields)
    {
        return $this->rule("required_with_all:" . implode(',', $fields));
    }

    public function requiredWithout(string ...$fields)
    {
        return $this->rule("required_without:" . implode(',', $fields));
    }

    public function requiredWithoutAll(string ...$fields)
    {
        return $this->rule("required_without_all:" . implode(',', $fields));
    }

    public function distinct()
    {
        return $this->rule('distinct');
    }

    public function same(string $field)
    {
        return $this->rule("same:$field");
    }

    public function excludeIf(string $field, $value)
    {
        return $this->rule("exclude_if:$field,$value");
    }

    public function excludeUnless(string $field, $value)
    {
        return $this->rule("exclude_unless:$field,$value");
    }

    public function exists(string $table, string $column)
    {
        return $this->rule("exists:$table,$column");
    }

    public function filled()
    {
        return $this->rule('filled');
    }

    public function present()
    {
        return $this->rule('present');
    }

    public function url()
    {
        return $this->rule('url');
    }

    public function uuid()
    {
        return $this->rule('uuid');
    }

    public function unique(string $tableOrModel, string $column)
    {
        return $this->rule("unique:$tableOrModel,$column");
    }
}