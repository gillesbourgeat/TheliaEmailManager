<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager\Driver;

use TheliaEmailManager\Query\EmailHistoryQuery;
use TheliaEmailManager\Entity\EmailEntity;
use TheliaEmailManager\Entity\EmailEntityCollection;
use TheliaEmailManager\Model\EmailManagerHistory;
use TheliaEmailManager\Model\EmailManagerHistoryEmail;
use TheliaEmailManager\Model\EmailManagerTraceQuery;
use TheliaEmailManager\Service\EmailService;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class PropelTraceDriver implements TraceDriverInterface
{
    /** @var EmailService */
    protected $emailService;

    /**
     * PropelTraceDriver constructor.
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @inheritdoc
     */
    public function find(EmailHistoryQuery $query)
    {
        $query = $query->getQuery();

        $collection = new EmailEntityCollection();

        // @todo

        return $collection;
    }

    /**
     * @inheritdoc
     */
    public function delete(EmailHistoryQuery $query)
    {
        $query = EmailManagerTraceQuery::create();

        // @todo

        return $query->delete();
    }

    /**
     * @inheritdoc
     */
    public function push(EmailEntity $emailEntity)
    {
        $history = (new EmailManagerHistory())
            ->setTraceId($emailEntity->getTraceId())
            ->setStatus($emailEntity->getStatus())
            ->setInfo($emailEntity->getInfo())
            ->setSubject($emailEntity->getSubject())
            ->setBody($emailEntity->getBody());

        $history->save();

        foreach ($emailEntity->getFrom() as $email => $name) {
            (new EmailManagerHistoryEmail())
                ->setEmailId($this->emailService->getEmailManagerEmail($email, $name)->getId())
                ->setType('from')
                ->setHistoryId($history->getId())
                ->save();
        }

        foreach ($emailEntity->getTo() as $email => $name) {
            (new EmailManagerHistoryEmail())
                ->setEmailId($this->emailService->getEmailManagerEmail($email, $name)->getId())
                ->setType('to')
                ->setHistoryId($history->getId())
                ->save();
        }

        foreach ($emailEntity->getCc() as $email => $name) {
            (new EmailManagerHistoryEmail())
                ->setEmailId($this->emailService->getEmailManagerEmail($email, $name)->getId())
                ->setType('cc')
                ->setHistoryId($history->getId())
                ->save();
        }

        foreach ($emailEntity->getBcc() as $email => $name) {
            (new EmailManagerHistoryEmail())
                ->setEmailId($this->emailService->getEmailManagerEmail($email, $name)->getId())
                ->setType('bcc')
                ->setHistoryId($history->getId())
                ->save();
        }

        foreach ($emailEntity->getReplyTo() as $email => $name) {
            (new EmailManagerHistoryEmail())
                ->setEmailId($this->emailService->getEmailManagerEmail($email, $name)->getId())
                ->setType('rt')
                ->setHistoryId($history->getId())
                ->save();
        }
    }
}
