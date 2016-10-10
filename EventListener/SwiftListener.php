<?php

namespace TheliaEmailManager\EventListener;

use Thelia\Model\Lang;
use Thelia\Model\LangQuery;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\SwiftEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftListener implements EventSubscriberInterface
{
    protected $emailManagerTraceCache = [];

    public function send(SwiftEvent $event)
    {
        if (null !== $emailManagerTrace = $this->getEmailManagerTrace($event)) {

            unset($this->emailManagerTraceCache[spl_object_hash($event->getSwiftEvent())]);
        }
        exit;
    }

    public function beforeSend(SwiftEvent $event)
    {
        if (null !== $emailManagerTrace = $this->getEmailManagerTrace($event)) {

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
        $objectIdentifier = spl_object_hash($event->getSwiftEvent());
        if (!isset($this->emailManagerTraceCache[$objectIdentifier])) {
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

                $this->emailManagerTraceCache[$objectIdentifier] = $emailManagerTrace;
            }

            return null;
        }

        return $this->emailManagerTraceCache[$objectIdentifier];
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
}
