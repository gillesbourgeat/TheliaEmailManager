<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
