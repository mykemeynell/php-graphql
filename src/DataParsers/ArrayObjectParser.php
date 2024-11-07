<?php

namespace GraphQL\DataParsers;

use GraphQL\DataParsers\Parser;
use GraphQL\Literals\Literal;

class ArrayObjectParser implements Parser
{
    protected string $result = '';
    protected string $separator = '';

    /**
     * @param $val
     * @return void
     */
    private function value($val): void
    {
        if($val instanceof Literal) {
            $this->result .= $val->value();
            return;
        }

        if (is_int($val) || is_float($val)) {
            $this->result .= $val;
        } elseif (is_string($val)) {
            $this->result .= '"' . str_replace('"', '\"', $val) . '"';
        } elseif (is_bool($val)) {
            $this->result .= $val ? 'true' : 'false';
        } elseif (is_array($val) || !is_assoc($val)) {
            $this->iterateArray($val);
            $this->result .= ', ';
        } elseif (is_object($val) || is_assoc($val)) {
            $this->iterateObject($val);
            $this->result .= ', ';
        } else {
            $this->result .= $val;
        }
    }

    /**
     * @inheritDoc
     */
    public function parse(mixed $data): string|int|float|bool
    {
        if (is_array($data) && !is_assoc($data)) {
            $this->iterateArray($data);
        } elseif (is_object($data) || is_assoc($data)) {
            $this->iterateObject($data);
        }

        return $this->result;
    }

    private function iterateArray($data) : string
    {
        $this->result .= '[';
        $this->separator = '';
        foreach ($data as $key => $val) {
            self::value($val);
            if (!is_int($val)) {
                $this->separator = ', ';
            }
        }

        $this->result .= ']';
        return $this->result;
    }

    private function iterateObject($data): string
    {
        $this->result .= '{';
        $this->separator = '';
        foreach ($data as $key => $val) {

            $this->result .= $this->separator . $key . ':';
            self::value($val);
            $this->separator = ', ';
        }
        $this->result .= '}';
        return $this->result;
    }
}
