<?php

namespace GraphQL;

use GraphQL\Concerns\BuildsQuery;

class Query extends GraphBuilder
{
    use BuildsQuery;

    public function build(): string
    {
        $query = "query {\n";
        foreach ($this->fields as $field) {
            $query .= $this->buildField($field, 2);
        }
        $query .= "}\n";
        return $query;
    }
}

