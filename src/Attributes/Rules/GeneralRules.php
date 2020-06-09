<?php


namespace Larapie\Actions\Attributes\Rules;


trait GeneralRules
{
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

    public function filled(){
        return $this->rule('filled');
    }
}