<?php

namespace GraphQL;

class Fragment
{
    private string $name;
    private string $type;
    private array $fields;

    public function __construct(string $name, string $type, array $fields)
    {
        $this->name = $name;
        $this->type = $type;
        $this->fields = $fields;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function build(): string
    {
        $fields = implode(' ', $this->fields);
        return "fragment {$this->name} on {$this->type} { {$fields} }";
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
