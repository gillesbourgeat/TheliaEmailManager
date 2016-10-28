<?php

namespace TheliaEmailManager\Entity;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailEntityCollection extends \ArrayObject
{
    /**
     * @param int $offset
     * @param EmailEntity $value
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof EmailEntity)) {
            throw new \InvalidArgumentException('Invalid argument value');
        }

        parent::offsetSet($offset, $value);
    }
}
