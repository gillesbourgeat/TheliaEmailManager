<?php

namespace TheliaEmailManager\EventListener;

use TheliaEmailManager\Event\EmailEvent;
use TheliaEmailManager\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailListener implements EventSubscriberInterface
{
    public function createOrUpdate(EmailEvent $event)
    {
        $event->getEmailManagerEmail()->save();
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::EMAIL_CREATE => ['createOrUpdate', 128],
            Events::EMAIL_UPDATE => ['createOrUpdate', 128]
        ];
    }
}
