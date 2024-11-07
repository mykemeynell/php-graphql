<?php

namespace GraphQL\DataParsers;

interface Parser
{
    /**
     * Parse the data.
     *
     * @param mixed $data
     *
     * @return string|int|float|bool
     */
    public function parse(mixed $data): string|int|float|bool;
}
