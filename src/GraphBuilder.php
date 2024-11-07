<?php

namespace GraphQL;

use Firebase\JWT\BeforeValidException;
use GraphQL\Concerns\BuildsQuery;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Stringable;

abstract class GraphBuilder implements \Stringable
{
    use BuildsQuery;

    /**
     * @var Field[]
     */
    protected array $fields = [];

    public function build(): string
    {
        $output = [];

        $output[] = static::TYPE . " {\n";

        foreach ($this->fields as $field) {
            $output[] = $this->buildField($field, 2);
        }

        $output[] = "}\n";

        return implode("\n", Arr::flatten($output));
    }

    public function getFirstField(): ?Field
    {
        return first($this->fields);
    }

    public function addField(Field $field): static
    {
        $this->fields[] = $field;
        return $this;
    }

    public function addFields(array $fields): static
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->build();
    }
}
