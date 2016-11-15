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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TheliaEmailManager\Driver\TraceDriverInterface;
use TheliaEmailManager\Entity\EmailEntity;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\SwiftResponseEvent;
use TheliaEmailManager\Event\SwiftSendEvent;
use TheliaEmailManager\Event\SwiftTransportEvent;
use TheliaEmailManager\Event\TraceEvent;
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Service\EmailService;
use TheliaEmailManager\Service\TraceService;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftListener implements EventSubscriberInterface
{
    /** @var EmailService */
    protected $emailService;

    /** @var TraceService */
    protected $traceService;

    /** @var TraceDriverInterface */
    protected $traceDriver;

    /** @var EmailManagerTrace */
    protected $lastEmailManagerTrace;

    /** @var \Swift_TransportException|null */
    protected $lastSwiftException;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var bool */
    protected $debug;

    /**
     * SwiftListener constructor.
     * @param EmailService $emailService
     * @param TraceService $traceService
     * @param TraceDriverInterface $traceDriver
     * @param EventDispatcherInterface $eventDispatcher
     * @param bool
     */
    public function __construct(
        EmailService $emailService,
        TraceService $traceService,
        TraceDriverInterface $traceDriver,
        EventDispatcherInterface $eventDispatcher,
        $debug
    ) {
        $this->emailService = $emailService;
        $this->traceService = $traceService;
        $this->traceDriver = $traceDriver;
        $this->eventDispatcher = $eventDispatcher;
        $this->debug = $debug;
    }

    /**
     * Step 4
     *
     * @param SwiftSendEvent $event
     */
    public function send(SwiftSendEvent $event)
    {
        if ($this->lastSwiftException === null
            && TheliaEmailManager::getEnableHistory()
            && !$this->lastEmailManagerTrace->getDisableHistory()
        ) {
            $this->traceDriver->push(
                (new EmailEntity())
                    ->setInfo($event->getSwiftEvent()->getResult())
                    ->setStatus(TheliaEmailManager::STATUS_SUCCESS)
                    ->hydrateBySwiftMimeMessage($event->getSwiftEvent()->getMessage())
                    ->setTraceId($this->lastEmailManagerTrace->getId())
            );
        }
    }

    /**
     * Step 3
     *
     * @param SwiftSendEvent $event
     */
    public function beforeSend(SwiftSendEvent $event)
    {
        if (null === $emailManagerTrace = $this->traceService->getEmailManagerTrace($this->getFullTrace())) {
            return;
        }

        $this->lastEmailManagerTrace = $emailManagerTrace;

        $emailManagerTrace->setNumberOfCatch($emailManagerTrace->getNumberOfCatch() + 1);
        $this->eventDispatcher->dispatch(Events::TRACE_UPDATE, new TraceEvent($emailManagerTrace));

        if (TheliaEmailManager::getDisableSending() || $emailManagerTrace->getDisableSending()) {
            $event->getSwiftEvent()->cancelBubble(true);
            return;
        }

        $message = $event->getSwiftEvent()->getMessage();

        if (!$emailManagerTrace->getForceSameCustomerDisable()) {
            if (null !== $to = $message->getTo()) {
                foreach ($to as $email => $name) {
                    /** @var EmailManagerEmail $find */
                    if (null !== $find = $this->emailService->getEmailManagerEmail($email, $name)) {
                        if ($find->getDisableSend()) {
                            unset($to[$email]);
                        }
                    }
                }

                $message->setTo($to);

                if (!count($to)) {
                    $event->getSwiftEvent()->cancelBubble(true);
                    return;
                }
            }

            if (null !== $bcc = $message->getBcc()) {
                foreach ($bcc as $email => $name) {
                    /** @var EmailManagerEmail $find */
                    if (null !== $find = $this->emailService->getEmailManagerEmail($email, $name)) {
                        if ($find->getDisableSend()) {
                            unset($bcc[$email]);
                        }
                    }
                }
                $message->setBcc($bcc);
            }

            if (null !== $cc = $message->getCc()) {
                foreach ($cc as $email => $name) {
                    /** @var EmailManagerEmail $find */
                    if (null !== $find = $this->emailService->getEmailManagerEmail($email, $name)) {
                        if ($find->getDisableSend()) {
                            unset($cc[$email]);
                        }
                    }
                }
                $message->setCc($cc);
            }
        }

        // force redirect
        if (count(TheliaEmailManager::getRedirectAllTo())) {
            $message->setTo(TheliaEmailManager::getRedirectAllTo());
            $message->setCc(null);
            $message->setBcc(null);
            return;
        }

        // add Bcc
        if (count($emailManagerTrace->getEmailBcc())) {
            $current = (null !== $message->getBcc()) ? $event->getSwiftEvent()->getMessage()->getBcc() : [];

            foreach ($emailManagerTrace->getEmailBcc() as $email) {
                $current[$email] = null;
            }
            $message->setBcc($current);
        }

        // redirect
        if (count($emailManagerTrace->getEmailRedirect())) {
            $to = [];
            foreach ($emailManagerTrace->getEmailRedirect() as $email) {
                $to[$email] = null;
            }
            $message->setTo($to);
        }

        // if Swift_TransportException
        if ($this->lastSwiftException !== null) {
            if (TheliaEmailManager::getEnableHistory() && !$this->lastEmailManagerTrace->getDisableHistory()) {
                $this->traceDriver->push(
                    (new EmailEntity())
                        ->setInfo($this->lastSwiftException->getMessage())
                        ->setStatus(TheliaEmailManager::STATUS_ERROR)
                        ->hydrateBySwiftMimeMessage($event->getSwiftEvent()->getMessage())
                        ->setTraceId($emailManagerTrace->getId())
                );
            }
            $event->getSwiftEvent()->cancelBubble(true);

            if ($this->debug) {
                throw $this->lastSwiftException;
            }
        }
    }

    /**
     * Step 2
     *
     * @param SwiftTransportEvent $event
     */
    public function exceptionThrown(SwiftTransportEvent $event)
    {
        $this->lastSwiftException = $event->getSwiftEvent()->getException();
        $event->getSwiftEvent()->cancelBubble(true);
    }

    /**
     * Step 1
     *
     * @param SwiftResponseEvent $event
     */
    public function responseReceived(SwiftResponseEvent $event)
    {
        $this->lastSwiftException = null;
    }

    /**
     * @return array
     */
    protected function getFullTrace()
    {
        return debug_backtrace(2);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::SWIFT_RESPONSE_RECEIVED => ['responseReceived', 128],
            Events::SWIFT_EXCEPTION_THROWN => ['exceptionThrown', 128],
            Events::SWIFT_SEND_PERFORMED => ['send', 128],
            Events::SWIFT_BEFORE_SEND_PERFORMED => ['beforeSend', 128]
        ];
    }
}
