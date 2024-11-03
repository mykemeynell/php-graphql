<?php

namespace GraphQL;

class Field
{
    public function __construct(
        public string $name,
        public array $args = [],
        public array $subFields = [],
        public ?string $alias = null
    ) {}
}
