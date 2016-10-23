<?php

namespace TheliaEmailManager\Form;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class DisableEmailConfirmationForm extends BaseForm
{
    /**
     * @return string
     */
    public function getName()
    {
        return Forms::DISABLE_EMAIL_CONFIRMATION;
    }

    public function buildForm()
    {
    }
}
