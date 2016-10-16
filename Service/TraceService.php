<?php

namespace TheliaEmailManager\Service;

use Thelia\Model\Lang;
use Thelia\Model\LangQuery;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceService
{
    /** @var EmailManagerTrace[] */
    protected $emailManagerTraceCache = [];

    /**
     * @param array $trace
     * @return EmailManagerTrace|null
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEmailManagerTrace(array $trace)
    {
        $minTrace = $this->getMineTrace($trace);

        if (count($minTrace)) {
            $hash = $this->getHash($minTrace);

            if (!isset($this->emailManagerTraceCache[$hash])) {
                if (null === $emailManagerTrace = EmailManagerTraceQuery::create()->findOneByHash($hash)) {
                    $emailManagerTrace = (new EmailManagerTrace())
                        ->setHash($hash)
                        ->setDetail(serialize($trace));

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
     * @return array
     */
    protected function getMineTrace(array $trace)
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
}
