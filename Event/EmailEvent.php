<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Event;

use Symfony\Component\EventDispatcher\Event;
use TheliaEmailManager\Model\EmailManagerEmail;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailEvent extends Event
{
    /** @var EmailManagerEmail */
    protected $emailManagerEmail;

    /**
     * @param EmailManagerEmail $emailManagerEmail
     */
    public function __construct(EmailManagerEmail $emailManagerEmail)
    {
        $this->emailManagerEmail = $emailManagerEmail;
    }

    /**
     * @return EmailManagerEmail
     */
    public function getEmailManagerEmail()
    {
        return $this->emailManagerEmail;
    }

    /**
     * @param EmailManagerEmail $emailManagerEmail
     * @return $this
     */
    public function setEmailManagerEmail(EmailManagerEmail $emailManagerEmail)
    {
        $this->emailManagerEmail = $emailManagerEmail;
        return $this;
    }
}
