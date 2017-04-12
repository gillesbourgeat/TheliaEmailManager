<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager\Form;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class GenericForm extends BaseForm
{
    /**
     * @return string
     */
    public function getName()
    {
        return Forms::GENERIC;
    }

    public function buildForm()
    {
    }
}
