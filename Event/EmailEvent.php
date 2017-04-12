<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
