<?php

namespace GraphQL;

use Exception;
use GraphQL\Concerns\BuildsQuery;
use Illuminate\Support\Arr;

class Field
{
    public function __construct(
        protected string $name,
        protected mixed $value = null,
        protected array $arguments = [],
        protected array $subFields = [],
        protected ?string $alias = null
    )
    {}

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setValue(mixed $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setArguments(array $arguments): static
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function hasArguments(): bool
    {
        return count($this->arguments) > 0;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;
        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function hasAlias(): bool
    {
        return $this->alias !== null;
    }

    public function hasSubFields(): bool
    {
        return count($this->subFields) > 0;
    }

    public function addSubField(string|Field $field, ?array $nest = null): static
    {
        if($field instanceof Field && !is_null($nest)) {
            throw new Exception("Cannot create nested fields while passing '%s'", Field::class);
        }

        if(!is_null($nest) && is_string($field)) {
            // Split the dot notation into parts
            $keys = explode('.', $field);

            // Create nested Field instances from the dot notation
            $nestedField = array_reduce(
                array_reverse($keys),
                fn ($carry, $key) => new Field(
                    name: $key,
                    subFields: is_null($carry) ? Arr::map($nest, fn ($k) => new Field($k)) : [$carry]
                ),
                null
            );

            $this->subFields[] = $nestedField;

            return $this;
        }

        $this->subFields[] = $field instanceof Field
            ? $field : new Field($field);

        return $this;
    }

    public function addSubFields(array $fields): static
    {
        foreach ($fields as $field) {
            $this->addSubField($field);
        }
        return $this;
    }

    public function getSubFields(): array
    {
        return $this->subFields;
    }
}
