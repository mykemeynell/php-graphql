<?php

namespace GraphQL\Literals;

class Literal
{
    public function __construct(
        protected $value
    ) {}

    public static function make($value): static
    {
        return new static($value);
    }

    public function value()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }
}
