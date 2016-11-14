<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

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
