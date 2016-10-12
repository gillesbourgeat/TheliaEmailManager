<?php

namespace TheliaEmailManager\EventListener;

use Thelia\Model\Lang;
use Thelia\Model\LangQuery;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\SwiftEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerEmailQuery;
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

    protected $emailManagerEmailCache = [];

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
        if (null !== $emailManagerTrace = $this->getEmailManagerTrace($event)) {
            $emailManagerTrace->setNumberOfCatch($emailManagerTrace->getNumberOfCatch() + 1)->save();
        }
    }

    public function beforeSend(SwiftEvent $event)
    {
        if (null !== $emailManagerTrace = $this->getEmailManagerTrace($event)) {
            if ($emailManagerTrace->getDisableSending()) {
                $event->getSwiftEvent()->cancelBubble(true);
                return;
            }

            $message = $event->getSwiftEvent()->getMessage();

            if (!$emailManagerTrace->getForceSameCustomerDisable()) {
                if (null !== $to = $message->getTo()) {
                    foreach ($to as $email => $name) {
                        /** @var EmailManagerEmail $find */
                        if (null !== $find = $this->getEmailManagerEmailFromEmail($email) && $find->getDisableSend()) {
                            unset($to[$email]);
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
                        if (null !== $find = $this->getEmailManagerEmailFromEmail($email) && $find->getDisableSend()) {
                            unset($bcc[$email]);
                        }
                    }
                    $message->setBcc($bcc);
                }

                if (null !== $cc = $message->getCc()) {
                    foreach ($cc as $email => $name) {
                        /** @var EmailManagerEmail $find */
                        if (null !== $find = $this->getEmailManagerEmailFromEmail($email) && $find->getDisableSend()) {
                            unset($cc[$email]);
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

            return $emailManagerTrace;
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

        $this->emailManagerEmailCache[$email] = EmailManagerEmailQuery::create()->findOneByEmail($email);

        return $this->emailManagerEmailCache[$email];
    }
}
