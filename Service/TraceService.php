<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Model\Lang;
use Thelia\Model\LangQuery;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Event\TraceEvent;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceService
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var EmailManagerTrace[] */
    protected $emailManagerTraceCache = [];

    /** @var string */
    protected $environment;

    /** @var bool */
    protected $cli;

    public function __construct(EventDispatcherInterface $eventDispatcher, $environment)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->environment = $environment;
        $this->cli = php_sapi_name() === "cli";
    }

    /**
     * @param array $trace
     * @param bool $force pass to true for ignore cache
     * @return EmailManagerTrace|null
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEmailManagerTrace(array $trace, $force = false)
    {
        if ($this->environment === 'test') {
            return null;
        }

        $minTrace = $this->getMineTrace($trace);

        if (count($minTrace)) {
            $hash = $this->getHash($minTrace);

            if (!isset($this->emailManagerTraceCache[$hash]) || !$force) {
                if (null === $emailManagerTrace = EmailManagerTraceQuery::create()->findOneByHash($hash)) {
                    $emailManagerTrace = (new EmailManagerTrace())
                        ->setHash($hash)
                        ->setEnvironment($this->environment)
                        ->setCli($this->cli)
                        ->setDetail(serialize($trace));

                    $title = $this->getTitleFromTrace($minTrace);

                    $languages = LangQuery::create()->filterByActive(true)->find();

                    /** @var Lang $language */
                    foreach ($languages as $language) {
                        $emailManagerTrace->setLocale($language->getLocale())->setTitle($title);
                    }

                    $this->linkToParentTrace($emailManagerTrace);

                    $this->eventDispatcher->dispatch(Events::TRACE_CREATE, new TraceEvent($emailManagerTrace));
                } else {
                    // override by parent
                    if (null !== $parent = $emailManagerTrace->getEmailManagerTraceRelatedByParentId()) {
                        $emailManagerTrace->setEmailBcc($parent->getEmailBcc())
                            ->setEmailRedirect($parent->getEmailRedirect())
                            ->setDisableHistory($parent->getDisableHistory())
                            ->setDisableSending($parent->getDisableSending())
                            ->setForceSameCustomerDisable($parent->getForceSameCustomerDisable());
                    }
                }

                $this->emailManagerTraceCache[$hash] = $emailManagerTrace;
            }

            return $this->emailManagerTraceCache[$hash];
        }

        return null;
    }

    /**
     * @param EmailManagerTrace $childrenEmailManagerTrace
     */
    protected function linkToParentTrace(EmailManagerTrace $childrenEmailManagerTrace)
    {
        $emailManagerTraces = EmailManagerTraceQuery::create()->filterByParentId(null)->find();

        $possibleChildren = unserialize($childrenEmailManagerTrace->getDetail());

        /** @var EmailManagerTrace $emailManagerTrace */
        foreach ($emailManagerTraces as $emailManagerTrace) {
            if ($this->getMineTrace($possibleChildren)[0]
                === $this->getMineTrace(unserialize($emailManagerTrace->getDetail()))[0]) {
                $childrenEmailManagerTrace->setParentId($emailManagerTrace->getId());
                break;
            }
        }
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
                if (!in_array($entry['class'] . '::' . $entry['function'], $return)) {
                    $return[] = $entry['class'] . '::' . $entry['function'];
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
        return implode(' | ', $trace);
    }

    /**
     * @param array $trace
     * @return string md5
     */
    protected function getHash($trace)
    {
        return md5(serialize($trace) . $this->environment . $this->cli);
    }
}
