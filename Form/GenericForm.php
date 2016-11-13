<?php

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
