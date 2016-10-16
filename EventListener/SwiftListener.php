<?php

namespace TheliaEmailManager\EventListener;

use TheliaEmailManager\Driver\TraceDriverInterface;
use TheliaEmailManager\Entity\EmailEntity;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\SwiftEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Service\EmailService;
use TheliaEmailManager\Service\TraceService;

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

    /**
     * SwiftListener constructor.
     * @param EmailService $emailService
     * @param TraceService $traceService
     * @param TraceDriverInterface $traceDriver
     */
    public function __construct(
        EmailService $emailService,
        TraceService $traceService,
        TraceDriverInterface $traceDriver
    ) {
        $this->emailService = $emailService;
        $this->traceService = $traceService;
        $this->traceDriver = $traceDriver;
    }

    public function send(SwiftEvent $event)
    {
        if (null === $emailManagerTrace = $this->traceService->getEmailManagerTrace($this->getFullTrace())) {
            return;
        }

        if (!$emailManagerTrace->getDisableHistory()) {
            /** @var \Swift_Mime_Message $message */
            $message = $event->getSwiftEvent()->getMessage();

            $this->traceDriver->push(
                (new EmailEntity())
                    ->hydrateBySwiftMimeMessage($message)
                    ->setTraceId($emailManagerTrace->getId())
            );
        }
    }

    public function beforeSend(SwiftEvent $event)
    {
        if (null === $emailManagerTrace = $this->traceService->getEmailManagerTrace($this->getFullTrace())) {
            return;
        }

        $emailManagerTrace->setNumberOfCatch($emailManagerTrace->getNumberOfCatch() + 1)->save();

        if ($emailManagerTrace->getDisableSending()) {
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
            Events::SWIFT_SEND_PERFORMED => ['send', 128],
            Events::SWIFT_BEFORE_SEND_PERFORMED => ['beforeSend', 128]
        ];
    }
}
