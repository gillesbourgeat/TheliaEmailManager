<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager\Plugin;

use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\SwiftResponseEvent;
use TheliaEmailManager\Event\SwiftSendEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TheliaEmailManager\Event\SwiftTransportEvent;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftEventListenerPlugin implements \Swift_Events_SendListener, \Swift_Events_TransportExceptionListener, \Swift_Events_ResponseListener
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
     * @param \Swift_Events_ResponseEvent $evt
     */
    public function responseReceived(\Swift_Events_ResponseEvent $evt)
    {
        $this->dispatcher->dispatch(Events::SWIFT_RESPONSE_RECEIVED, new SwiftResponseEvent($evt));
    }

    /**
     * @param \Swift_Events_TransportExceptionEvent $evt
     */
    public function exceptionThrown(\Swift_Events_TransportExceptionEvent $evt)
    {
        $this->dispatcher->dispatch(Events::SWIFT_EXCEPTION_THROWN, new SwiftTransportEvent($evt));
    }

    /**
     * @param \Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $evt)
    {
        $this->dispatcher->dispatch(Events::SWIFT_BEFORE_SEND_PERFORMED, new SwiftSendEvent($evt));
    }

    /**
     * @param \Swift_Events_SendEvent $evt
     */
    public function sendPerformed(\Swift_Events_SendEvent $evt)
    {
        $this->dispatcher->dispatch(Events::SWIFT_SEND_PERFORMED, new SwiftSendEvent($evt));
    }
}
