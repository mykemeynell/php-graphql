<?php

namespace GraphQL;

use Illuminate\Support\Str;
use Stringable;

abstract class GraphBuilder implements Stringable
{
    /**
     * Select statements.
     *
     * @var array
     */
    protected array $select = [];

    /**
     * Argument statements.
     *
     * @var array
     */
    protected array $arguments = [];

    public function __construct(
        protected readonly string $name
    ){}

    /**
     * Get the name.
     *
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * Add a select statement.
     *
     * @param array|string $field
     * @return static
     */
    public function addSelect(array|string $field): static
    {
        if (is_array($field)) {
            foreach ($field as $index => $item) {
                if (!$this->isDuplicate($item)) {
                    $this->select[$index] = $item;
                }
            }
        } else {
            if (!$this->isDuplicate($field)) {
                $this->select[] = $field;
            }
        }
        return $this;
    }

    /**
     * Add arguments.
     *
     * @param array $arguments
     * @return self
     */
    public function addArguments(array $arguments): static
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * Set the select values.
     *
     * @return array
     */
    public function getSelect(): array
    {
        return $this->select;
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Create a query from a set of arguments.
     *
     * @param string $name
     * @param array|string $select
     * @param array $arguments
     * @return string
     */
    public static function getPreparedQueryFrom(string $name, array|string $select, array $arguments = []): string
    {
        return (new static($name))->addSelect($select)->addArguments($arguments)->convert();
    }

    /**
     * Convert the statement to a string notation.
     *
     * @return string
     */
    protected function convert(): string
    {
        $str = $this->getType() . '{' . $this->getName();

        if (!empty($this->getArguments())) {
            $str .= '(' . $this->convertArguments() . ')';
        }

        $str .= '{';
        $str .= $this->convertSelect();
        $str .= '}}';

        return $str;
    }

    /**
     * Convert the select statements.
     *
     * @return array|string
     */
    private function convertSelect(): array|string
    {
        $str = '';

        foreach ($this->getSelect() as $index => $item) {
            if (is_array($item)) {
                $str .= $index . '{';
                $str .= $this->disArraySelect($item);
                $str .= '},';
            } else {
                $str .= $item . ',';
            }
        }

        return substr_replace($str ,'', -1);
    }

    /**
     * Convert arguments to statement output.
     *
     * @return array|string
     */
    private function convertArguments(): array|string
    {
        $str = '';

        foreach ($this->getArguments() as $index => $value) {
            $str .= $index . ':';

            if (is_array($value)) {
                $str .= $this->disArrayArguments($value);
            } else {
                $str .= $this->checkArgumentsString($value);
            }
        }

        return substr_replace($str ,'', -1);
    }

    /**
     * Perform check of argument output string.
     *
     * @param $string
     * @return string
     */
    private function checkArgumentsString($string): string
    {
        $str = '';

        if (is_int($string) || is_float($string)) {
            $str .= $string;
        } elseif (is_bool($string)) {
            $str .= $string ? 'true' : 'false';
        } elseif (is_null($string)) {
            $str .= 'null';
        } else {
            $str .= '"' . $string . '"';
        }

        return $str . ',';
    }

    /**
     * Build select string.
     *
     * @param $array
     * @return array|string
     */
    private function disArraySelect($array): array|string
    {
        $str = '';

        foreach ($array as $index => $value) {
            if (is_array($value)) {
                $str .= $index . '{' . $this->disArraySelect($value) . '},';
            } else {
                $str .= $value . ',';
            }
        }

        return substr_replace($str ,'', -1);
    }

    /**
     * Build array arguments.
     *
     * @param $array
     * @return string
     */
    private function disArrayArguments($array): string
    {
        $str = '';

        if ($this->isArrayAssociative($array)) {
            $str .= '{';
        } else {
            $str .= '[';
        }

        foreach ($array as $index => $value) {
            if (is_string($index)) {
                $str .= $index . ':';
            }

            if (is_array($value)) {
                $str .= $this->disArrayArguments($value);
            } else {
                $str .= $this->checkArgumentsString($value);
            }
        }

        $str = substr_replace($str ,'', -1);

        if ($this->isArrayAssociative($array)) {
            $str .= '},';
        } else {
            $str .= '],';
        }

        return $str;
    }

    /**
     * Determine if an array is associative.
     *
     * @param $array
     * @return bool
     */
    private function isArrayAssociative($array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Determine if a field already exists within the select group.
     *
     * @param $field
     * @return bool
     */
    protected function isDuplicate($field): bool
    {
        return in_array($field, $this->getSelect(), true);
    }

    /**
     * Get the query type.
     *
     * @return string
     */
    protected function getType(): string
    {
        $constant = sprintf("%s::TYPE", static::class);

        if(defined($constant)) {
            return constant($constant);
        }

        return strtolower(
            Str::afterLast(static::class, '\\')
        );
    }



    /**
     * Convert the query to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Convert the query to a string.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->convert();
    }
}
