<?php

namespace GraphQL\Concerns;

use GraphQL\Field;
use Illuminate\Support\Arr;

trait BuildsQuery
{
    protected function buildField(Field $field, int $indent): string
    {
        $indentStr = str_repeat(' ', $indent);
        $fieldStr = $indentStr;

        if ($field->alias) {
            $fieldStr .= $field->alias . ': ';
        }

        $fieldStr .= $field->name;

        if (!empty($field->args)) {
            $args = [];
            foreach ($field->args as $key => $value) {
                $args[] = "$key: " . $this->formatValue($value);
            }
            $fieldStr .= '(' . implode(', ', $args) . ')';
        }

        $subFields = Arr::undot(
            Arr::dot($field->subFields)
        );

        if (!blank($subFields)) {
            $fieldStr .= " {\n";
            foreach ($subFields as $subField) {
                if (is_string($subField)) {
                    $fieldStr .= $indentStr . "  $subField\n";
                } elseif ($subField instanceof Field) {
                    $fieldStr .= $this->buildField($subField, $indent + 2);
                }
            }
            $fieldStr .= $indentStr . "}\n";
        } else {
            $fieldStr .= "\n";
        }

        return $fieldStr;
    }
}
