<?php

namespace TheliaEmailManager\Plugin;

use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\SwiftEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftEventListenerPlugin implements \Swift_Events_SendListener
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /**
     * SwiftEventsEventListener constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $evt)
    {
        $this->dispatcher->dispatch(Events::SWIFT_BEFORE_SEND_PERFORMED, new SwiftEvent($evt));
    }

    /**
     * @param \Swift_Events_SendEvent $evt
     */
    public function sendPerformed(\Swift_Events_SendEvent $evt)
    {
        $this->dispatcher->dispatch(Events::SWIFT_SEND_PERFORMED, new SwiftEvent($evt));
    }
}
