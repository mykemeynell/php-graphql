<?php

namespace GraphQL;

use GraphQL\Concerns\BuildsQuery;

class Mutation extends GraphBuilder
{
    use BuildsQuery;

    public function build(): string
    {
        $mutation = "mutation {\n";
        foreach ($this->fields as $field) {
            $mutation .= $this->buildField($field, 2);
        }
        $mutation .= "}\n";
        return $mutation;
    }
}
