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
use TheliaEmailManager\Model\EmailManagerTrace;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceEvent extends Event
{
    /** @var EmailManagerTrace|null */
    protected $emailManagerTrace;

    /**
     * TraceEvent constructor.
     * @param EmailManagerTrace $emailManagerTrace
     */
    public function __construct(EmailManagerTrace $emailManagerTrace)
    {
        $this->emailManagerTrace = $emailManagerTrace;
    }

    /**
     * @return EmailManagerTrace
     */
    public function getEmailManagerTrace()
    {
        return $this->emailManagerTrace;
    }

    /**
     * @param EmailManagerTrace $emailManagerTrace
     * @return $this
     */
    public function setEmailManagerTrace(EmailManagerTrace $emailManagerTrace)
    {
        $this->emailManagerTrace = $emailManagerTrace;
        return $this;
    }
}
