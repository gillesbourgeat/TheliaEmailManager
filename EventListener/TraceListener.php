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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Model\Admin;
use Thelia\Model\AdminQuery;
use Thelia\Tools\URL;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\TraceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;
use TheliaEmailManager\TheliaEmailManager;
use TheliaNotification\Entity\NotificationEntity;
use TheliaNotification\Service\NotificationService;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceListener implements EventSubscriberInterface
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public function __construct(ContainerInterface $container, EventDispatcherInterface $eventDispatcher)
    {
        $this->container = $container;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create(TraceEvent $event)
    {
        $event->getEmailManagerTrace()->save();

        $this->notificationNewTrace($event->getEmailManagerTrace());
    }

    public function update(TraceEvent $event)
    {
        $event->getEmailManagerTrace()->save();
    }

    public function unlink(TraceEvent $event)
    {
        /** @var EmailManagerTrace $parent */
        if (null !== $parent = EmailManagerTraceQuery::create()->findOneById($event->getEmailManagerTrace()->getParentId())) {
            $event->getEmailManagerTrace()
                ->setEmailBcc($parent->getEmailBcc())
                ->setEmailRedirect($parent->getEmailRedirect())
                ->setDisableHistory($parent->getDisableHistory())
                ->setDisableSending($parent->getDisableSending())
                ->setForceSameCustomerDisable($parent->getForceSameCustomerDisable())
                ->setParentId(null);

            $this->eventDispatcher->dispatch(Events::TRACE_UPDATE, new TraceEvent($event->getEmailManagerTrace()));
        }
    }

    protected function notificationNewTrace(EmailManagerTrace $emailManagerTrace)
    {
        if ($this->container->has('thelia.notification.service')) {
            /** @var NotificationService $notificationService */
            $notificationService = $this->container->get('thelia.notification.service');

            $admins = AdminQuery::create()->find();

            /** @var Admin $admin */
            foreach ($admins as $admin) {
                $notification = (new NotificationEntity('email_manager_new_trace'))
                    ->toAdmins(AdminQuery::create()->find())
                    ->setByEmail(true)
                    ->setMessageType(NotificationEntity::MESSAGE_TYPE_HTML)
                    ->setType(NotificationEntity::TYPE_WARNING)
                    ->setUrl(
                        URL::getInstance()->absoluteUrl('/admin/email-manager/trace/' . $emailManagerTrace->getId())
                    )
                    ->setTitle(
                        Translator::getInstance()
                            ->trans("Email Manager : New trace detected", [], TheliaEmailManager::DOMAIN_NAME, $admin->getLocale())
                    )
                    ->setMessage(
                        Translator::getInstance()
                            ->trans("The Email Manager Module detected an new trace", [], TheliaEmailManager::DOMAIN_NAME, $admin->getLocale())
                        . '<br/>'
                        .explode(' | ', $emailManagerTrace->getTitle())[0]
                    );

                $notificationService->sendNotification($notification);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::TRACE_UNLINK => ['unlink', 128],
            Events::TRACE_CREATE => ['create', 128],
            Events::TRACE_UPDATE => ['update', 128]
        ];
    }
}
