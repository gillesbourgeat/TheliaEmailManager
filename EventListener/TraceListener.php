<?php

namespace TheliaEmailManager\EventListener;

use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\TraceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceListener implements EventSubscriberInterface
{
    public function createOrUpdate(TraceEvent $event)
    {
        $event->getEmailManagerTrace()->save();
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::TRACE_CREATE => ['createOrUpdate', 128],
            Events::TRACE_UPDATE => ['createOrUpdate', 128]
        ];
    }
}
