<?php

namespace TheliaEmailManager\Query;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class ConditionQuery
{
    /** Comparison type. */
    const EQUAL = '=';

    /** Comparison type. */
    const NOT_EQUAL = '<>';

    /** Comparison type. */
    const ALT_NOT_EQUAL = '!=';

    /** Comparison type. */
    const GREATER_THAN = '>';

    /** Comparison type. */
    const LESS_THAN = '<';

    /** Comparison type. */
    const GREATER_EQUAL = '>=';

    /** Comparison type. */
    const LESS_EQUAL = '<=';

    /** Comparison type. */
    const IN = ' IN ';

    /** Comparison type. */
    const NOT_IN = ' NOT IN ';

    /** @var string */
    protected $condition;

    /** @var string */
    protected $column;

    /** @var mixed */
    protected $value;

    /**
     * MessageCriteria constructor.
     * @param $column
     * @param $condition
     * @param $value
     */
    public function __construct($column, $condition, $value)
    {
        $this->column = $column;
        $this->condition = $condition;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
