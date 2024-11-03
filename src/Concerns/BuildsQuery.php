<?php

namespace GraphQL\Concerns;

use GraphQL\Field;
use GraphQL\Fragment;
use Illuminate\Support\Arr;

trait BuildsQuery
{
    protected function buildField(Field $field, int $indent): string
    {
        $result = str_repeat(' ', $indent);

        if ($field->getAlias()) {
            $result .= $field->getAlias() . ': ';
        }

        $result .= $field->getName();

        if (!empty($field->getArguments())) {
            $args = [];
            foreach ($field->getArguments() as $name => $value) {
                $args[] = "$name: " . $this->formatValue($value);
            }
            $result .= '(' . implode(', ', $args) . ')';
        }

        if (!empty($field->getDirectives())) {
            foreach ($field->getDirectives() as $directive) {
                $result .= ' ' . $directive->build();
            }
        }

        $subFields = $field->getSubFields();

        if (!empty($subFields)) {
            $result .= " {\n";
            foreach ($subFields as $subField) {
                if ($subField instanceof Field) {
                    $result .= $this->buildField($subField, $indent + 2);
                } elseif ($subField instanceof Fragment) {
                    $result .= str_repeat(' ', $indent + 2) . '...' . $subField->getName() . "\n";
                } else {
                    $result .= str_repeat(' ', $indent + 2) . $subField . "\n";
                }
            }
            $result .= str_repeat(' ', $indent) . "}\n";
        } else {
            $result .= "\n";
        }

        return $result;
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

        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
