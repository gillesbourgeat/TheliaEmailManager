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
     * @param bool $force pass to true for ignore cache
     * @return EmailManagerTrace|null
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEmailManagerTrace(array $trace, $force = false)
    {
        $minTrace = $this->getMineTrace($trace);

        if (count($minTrace)) {
            $hash = $this->getHash($minTrace);

            if (!isset($this->emailManagerTraceCache[$hash]) || !$force) {
                if (null === $emailManagerTrace = EmailManagerTraceQuery::create()->findOneByHash($hash)) {
                    $emailManagerTrace = (new EmailManagerTrace())
                        ->setHash($hash)
                        ->setDetail(serialize($trace));

                    $title = $this->getTitleFromTrace($minTrace);

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
        $return = [];

        foreach ($trace as $key => $entry) {
            if ($key >= 11 && isset($entry['class']) && isset($entry['function'])
                && strpos($entry['class'], 'TheliaEmailManager\\') !== 0
                && strpos($entry['class'], 'Symfony\Component\\') !== 0
                && strpos($entry['class'], 'Swift_') !== 0
                && strpos($entry['class'], 'Thelia\Mailer\\') !== 0
                && strpos($entry['class'], 'Thelia\Core\\') !== 0
                && strpos($entry['class'], 'Stack\\') !== 0
            ) {
                $return[] = $entry['class'] . '::' . $entry['function'];
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
        return implode(' | ', $trace);
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
