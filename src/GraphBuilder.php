<?php

namespace GraphQL;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Stringable;

abstract class GraphBuilder implements \Stringable
{
    protected array $fields = [];

    public function addField(string $name, array $args = [], array $subFields = [], array $directives = []): self
    {
        $field = new Field($name, $args, $subFields, $directives);
        $this->fields[] = $field;
        return $this;
    }

    public function addAliasedField(string $alias, string $name, array $args = [], array $subFields = [], array $directives = []): self
    {
        $field = new Field($name, $args, $subFields, $directives, $alias);
        $this->fields[] = $field;
        return $this;
    }

    abstract public function build(): string;

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->build();
    }
}
