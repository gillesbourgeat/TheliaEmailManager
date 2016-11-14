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

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftEvent extends Event
{
    /** @var \Swift_Events_SendEvent|null */
    protected $swiftEvent;

    /**
     * SendPerformedEvent constructor.
     * @param \Swift_Events_SendEvent $swiftEvent
     */
    public function __construct(\Swift_Events_SendEvent $swiftEvent)
    {
        $this->swiftEvent = $swiftEvent;
    }

    /**
     * @return \Swift_Events_SendEvent
     */
    public function getSwiftEvent()
    {
        return $this->swiftEvent;
    }
}
