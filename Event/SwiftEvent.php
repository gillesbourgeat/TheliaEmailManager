<?php

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
    public function getEvt()
    {
        return $this->swiftEvent;
    }
}
