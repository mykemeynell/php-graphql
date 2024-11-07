<?php

namespace GraphQL\Concerns;

use Exception;
use GraphQL\DataParsers\Parser;
use GraphQL\Field;
use GraphQL\Fragment;
use GraphQL\DataParsers\ArrayObjectParser;
use GraphQL\Literals\Literal;
use Illuminate\Support\Arr;

trait BuildsQuery
{
    public function buildField(Field $field, int $indent): array
    {
        $output = [];

        $line = str_repeat("  ", $indent);

        $line .= $field->getAlias() ?? $field->getName();

        if($field->hasAlias()) {
            $line .= ':' . $field->getName();
        }

        if($field->hasArguments()) {
            $line .= sprintf("(%s)", $this->parseArguments($field->getArguments()));
        }

        if($field->hasSubFields()) {
            $output[] = $line . '{';
            foreach ($field->getSubFields() as $subField) {
                $output[] = $this->buildField($subField, $indent + 2);
            }
            $output[] = str_repeat("  ", $indent) . '}';
        } else {
            $output[] = $line;
        }

        return $output;
    }

    private function value($value): string
    {
        if($value instanceof Literal) {
            return $value;
        }

        if (is_array($value) || is_object($value)) {
            $parser = app(ArrayObjectParser::class);
            if(!$parser instanceof Parser) {
                throw new Exception("Parsers must be an instance of '%s'.", Parser::class);
            }
            return $parser->parse($value);
        }

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

    private function parseArguments(array $arguments): string
    {
        $output = [];

        foreach($arguments as $name => $value) {
            $output[] = sprintf("%s: %s", $name, $this->value($value));
        }

        return implode(", ", $output);
    }
}
