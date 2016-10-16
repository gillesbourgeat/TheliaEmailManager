<?php

namespace TheliaEmailManager\Driver;

use TheliaEmailManager\Entity\EmailEntity;
use TheliaEmailManager\Model\EmailManagerHistory;
use TheliaEmailManager\Model\EmailManagerHistoryEmail;
use TheliaEmailManager\Service\EmailService;

class PropelTraceDriver implements TraceDriverInterface
{
    /** @var EmailService */
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function push(EmailEntity $emailEntity)
    {
        $history = (new EmailManagerHistory())
            ->setTraceId($emailEntity->getTraceId())
            ->setSubject($emailEntity->getSubject())
            ->setBody($emailEntity->getBody());

        $history->save();

        if (null !== $from = $emailEntity->getFrom()) {
            foreach ($from as $email => $name) {
                (new EmailManagerHistoryEmail())
                    ->setEmailId($this->emailService->getEmailManagerEmail($email)->getId())
                    ->setType('from')
                    ->setHistoryId($history->getId())
                    ->save();
            }
        }

        if (null !== $to = $emailEntity->getTo()) {
            foreach ($to as $email => $name) {
                (new EmailManagerHistoryEmail())
                    ->setEmailId($this->emailService->getEmailManagerEmail($email)->getId())
                    ->setType('to')
                    ->setHistoryId($history->getId())
                    ->save();
            }
        }

        if (null !== $cc = $emailEntity->getCc()) {
            foreach ($cc as $email => $name) {
                (new EmailManagerHistoryEmail())
                    ->setEmailId($this->emailService->getEmailManagerEmail($email)->getId())
                    ->setType('cc')
                    ->setHistoryId($history->getId())
                    ->save();
            }
        }

        if (null !== $bcc = $emailEntity->getBcc()) {
            foreach ($bcc as $email => $name) {
                (new EmailManagerHistoryEmail())
                    ->setEmailId($this->emailService->getEmailManagerEmail($email)->getId())
                    ->setType('bcc')
                    ->setHistoryId($history->getId())
                    ->save();
            }
        }

        if (null !== $replyTo = $emailEntity->getReplyTo()) {
            foreach ($replyTo as $email => $name) {
                (new EmailManagerHistoryEmail())
                    ->setEmailId($this->emailService->getEmailManagerEmail($email)->getId())
                    ->setType('rt')
                    ->setHistoryId($history->getId())
                    ->save();
            }
        }
    }
}
