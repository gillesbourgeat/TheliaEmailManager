<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

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
