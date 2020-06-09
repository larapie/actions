<?php


namespace Larapie\Actions\Attributes\Rules;


trait StringRules
{
    public function min(int $length)
    {
        return $this->rule("min:$length");
    }

    public function max(int $length)
    {
        return $this->rule("max:$length");
    }

    public function email()
    {
        return $this->rule('email:rfc,dns');
    }

    public function endsWith(string ...$values)
    {
        return $this->rule('ends_with:' . implode(',', $values));
    }

    public function in(string ...$values)
    {
        return $this->rule('in:' . implode(',', $values));
    }

    public function ip(){
        return $this->rule('ip');
    }

    public function ipv4(){
        return $this->rule('ipv4');
    }

    public function ipv6(){
        return $this->rule('ipv6');
    }

    public function json(){
        return $this->rule('json');
    }
}