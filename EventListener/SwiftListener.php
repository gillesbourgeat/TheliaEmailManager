<?php

namespace TheliaEmailManager\EventListener;

use Thelia\Model\Lang;
use Thelia\Model\LangQuery;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\SwiftEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerHistory;
use TheliaEmailManager\Model\EmailManagerHistoryEmail;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;
use TheliaEmailManager\Service\EmailService;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftListener implements EventSubscriberInterface
{
    /** @var EmailService */
    protected $emailService;

    /** @var EmailManagerEmail[] */
    protected $emailManagerEmailCache = [];

    /** @var EmailManagerTrace[] */
    protected $emailManagerTraceCache = [];

    /**
     * SwiftListener constructor.
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function send(SwiftEvent $event)
    {
        if (null === $emailManagerTrace = $this->getEmailManagerTrace($event)) {
            return;
        }

        $message = $event->getSwiftEvent()->getMessage();

        if (!$emailManagerTrace->getDisableHistory()) {
            $history = (new EmailManagerHistory())
                ->setTraceId($emailManagerTrace->getId())
                ->setSubject($message->getSubject())
                ->setBody($message->getBody());

            $history->save();

            if (null !== $from = $message->getFrom()) {
                foreach ($from as $email => $name) {
                    (new EmailManagerHistoryEmail())
                        ->setEmailId($this->getEmailManagerEmailFromEmail($email)->getId())
                        ->setType('from')
                        ->setHistoryId($history->getId())
                        ->save();
                }
            }

            if (null !== $to = $message->getTo()) {
                foreach ($to as $email => $name) {
                    (new EmailManagerHistoryEmail())
                        ->setEmailId($this->getEmailManagerEmailFromEmail($email)->getId())
                        ->setType('to')
                        ->setHistoryId($history->getId())
                        ->save();
                }
            }

            if (null !== $cc = $message->getCc()) {
                foreach ($cc as $email => $name) {
                    (new EmailManagerHistoryEmail())
                        ->setEmailId($this->getEmailManagerEmailFromEmail($email)->getId())
                        ->setType('cc')
                        ->setHistoryId($history->getId())
                        ->save();
                }
            }

            if (null !== $bcc = $message->getBcc()) {
                foreach ($bcc as $email => $name) {
                    (new EmailManagerHistoryEmail())
                        ->setEmailId($this->getEmailManagerEmailFromEmail($email)->getId())
                        ->setType('bcc')
                        ->setHistoryId($history->getId())
                        ->save();
                }
            }

            if (null !== $replyTo = $message->getReplyTo()) {
                foreach ($replyTo as $email => $name) {
                    (new EmailManagerHistoryEmail())
                        ->setEmailId($this->getEmailManagerEmailFromEmail($email)->getId())
                        ->setType('rt')
                        ->setHistoryId($history->getId())
                        ->save();
                }
            }
        }
    }

    public function beforeSend(SwiftEvent $event)
    {
        if (null === $emailManagerTrace = $this->getEmailManagerTrace($event)) {
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
                    if (null !== $find = $this->getEmailManagerEmailFromEmail($email)) {
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
                    if (null !== $find = $this->getEmailManagerEmailFromEmail($email)) {
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
                    if (null !== $find = $this->getEmailManagerEmailFromEmail($email)) {
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
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::SWIFT_SEND_PERFORMED => ['send', 128],
            Events::SWIFT_BEFORE_SEND_PERFORMED => ['beforeSend', 128]
        ];
    }


    /**
     * @param SwiftEvent $event
     * @return EmailManagerTrace|null
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function getEmailManagerTrace(SwiftEvent $event)
    {
        $trace = $this->getTrace();
        if (count($trace)) {
            $hash = $this->getHash($trace);

            if (!isset($this->emailManagerTraceCache[$hash])) {
                if (null === $emailManagerTrace = EmailManagerTraceQuery::create()->findOneByHash($hash)) {
                    $emailManagerTrace = (new EmailManagerTrace())
                        ->setHash($hash)
                        ->setDetail(serialize($this->getFullTrace()));

                    $title = $this->getTitleFromTrace($trace);

                    $languages = LangQuery::create()->filterByActive(true)->find();

                    /** @var Lang $language */
                    foreach ($languages as $language) {
                        $emailManagerTrace->setLocale($language->getLocale())->setTitle($title);
                    }

                    $emailManagerTrace->save();
                }

                $this->emailManagerTraceCache[$hash] = $emailManagerTrace;
            }

            return $this->emailManagerTraceCache[$hash];
        }

        return null;
    }

    /**
     * @param array $trace
     * @return string
     */
    protected function getTitleFromTrace($trace)
    {
        $title = [];

        foreach ($trace as $t) {
            $title[] = $t['class'] . '::' . $t['function'];
        }

        return implode(' | ', $title);
    }

    /**
     * @param array $trace
     * @return string md5
     */
    protected function getHash($trace)
    {
         return md5(serialize($trace));
    }

    /**
     * @return array
     */
    protected function getFullTrace()
    {
        $traces = debug_backtrace();

        $return = [];

        foreach ($traces as $key => $trace) {
            $return[] = [
                'function' => isset($trace['function']) ? $trace['function'] : '',
                'class' => isset($trace['class']) ? $trace['class'] : '',
                'file' => isset($trace['file']) ? $trace['file'] : '',
                'line' => isset($trace['line']) ? $trace['line'] : '',
            ];
        }

        return $return;
    }

    /**
     * @return array
     */
    protected function getTrace()
    {
        $traces = debug_backtrace();

        $return = [];

        foreach ($traces as $key => $trace) {
            if ($key >= 11) {
                if (isset($trace['class'])
                    && strpos($trace['class'], 'Thelia\Mailer\\') !== 0
                    && strpos($trace['class'], 'Thelia\Core\\') !== 0
                    && strpos($trace['class'], 'Symfony\Component\\') !== 0
                    && strpos($trace['class'], 'TheliaEmailManager\\') !== 0
                    && strpos($trace['class'], 'Stack\\') !== 0
                    && strpos($trace['class'], 'Swift_') !== 0
                ) {
                    $return[] = [
                        'function' => isset($trace['function']) ? $trace['function'] : '',
                        'class' => isset($trace['class']) ? $trace['class'] : ''
                    ];
                }
            }
        }

        return $return;
    }

    /**
     * @param string $email
     * @return EmailManagerEmail|null
     */
    protected function getEmailManagerEmailFromEmail($email)
    {
        if (isset($this->emailManagerEmailCache[$email])) {
            return $this->emailManagerEmailCache[$email];
        }

        $this->emailManagerEmailCache[$email] = $this->emailService->getEmailManagerEmail($email);

        return $this->emailManagerEmailCache[$email];
    }
}
