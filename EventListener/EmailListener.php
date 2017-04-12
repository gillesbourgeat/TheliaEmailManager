<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
