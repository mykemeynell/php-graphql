<?php

namespace GraphQL;

use GraphQL\Concerns\BuildsQuery;

class Field
{
    use BuildsQuery;

    public function __construct(
        private readonly string $name,
        private readonly array $args = [],
        private readonly array $subFields = [],
        private readonly array $directives = [],
        private readonly ?string $alias = null
    )
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getArguments(): array
    {
        return $this->args;
    }

    public function getSubFields(): array
    {
        return $this->subFields;
    }

    public function getDirectives(): array
    {
        return $this->directives;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }
}
