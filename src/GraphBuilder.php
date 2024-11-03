<?php

namespace GraphQL;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Stringable;

abstract class GraphBuilder implements \Stringable
{
    protected array $fields = [];

    public function addField(string $name, array $args = [], array $subFields = []): self
    {
        $field = new Field($name, $args, $subFields);
        $this->fields[] = $field;
        return $this;
    }

    public function addAliasedField(string $alias, string $name, array $args = [], array $subFields = []): self
    {
        $field = new Field($name, $args, $subFields, $alias);
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

    protected function formatValue($value): string
    {
        if (is_string($value)) {
            return '"' . addslashes($value) . '"';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_null($value)) {
            return 'null';
        }
        if (is_array($value)) {
            return json_encode($value);
        }
        return $value;
    }
}
