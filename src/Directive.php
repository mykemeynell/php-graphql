<?php

namespace GraphQL;

class Directive
{
    private string $name;
    private array $arguments;

    public function __construct(string $name, array $arguments = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function build(): string
    {
        $arguments = [];
        foreach ($this->arguments as $name => $value) {
            $arguments[] = "{$name}: " . $this->formatValue($value);
        }
        $argumentsString = implode(', ', $arguments);

        return "@{$this->name}(" . $argumentsString . ")";
    }

    private function formatValue($value): string
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
        return $value;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
