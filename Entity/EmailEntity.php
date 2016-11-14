<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Entity;

use TheliaEmailManager\Model\EmailManagerHistory;
use TheliaEmailManager\Model\EmailManagerHistoryEmail;
use TheliaEmailManager\Model\EmailManagerHistoryEmailQuery;
use TheliaEmailManager\Model\Map\EmailManagerEmailTableMap;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailEntity
{
    /** @var string */
    protected $id = "";

    /** @var string */
    protected $subject = "";

    /** @var string[] */
    protected $from = [];

    /** @var string[] */
    protected $to = [];

    /** @var string */
    protected $traceId = "";

    /** @var string */
    protected $body = "";

    /** @var string[] */
    protected $replyTo = [];

    /** @var string[] */
    protected $cc = [];

    /** @var string[] */
    protected $bcc = [];

    /** @var \DateTime */
    protected $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \string[] $from
     * @return $this
     */
    public function setFrom(array $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param \string[] $to
     * @return $this
     */
    public function setTo(array $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return string
     */
    public function getTraceId()
    {
        return $this->traceId;
    }

    /**
     * @param string $traceId
     * @return $this
     */
    public function setTraceId($traceId)
    {
        $this->traceId = $traceId;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param \string[] $replyTo
     * @return $this
     */
    public function setReplyTo(array $replyTo)
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param \string[] $cc
     * @return $this
     */
    public function setCc(array $cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param \string[] $bcc
     * @return $this
     */
    public function setBcc(array $bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * @param \Swift_Mime_Message $message
     * @return $this
     */
    public function hydrateBySwiftMimeMessage(\Swift_Mime_Message $message)
    {
        $this
            ->setBody($message->getBody())
            ->setSubject($message->getSubject())
            ->setDate((new \DateTime())->setTimestamp($message->getDate()));

        if (null !== $message->getTo()) {
            $this->setTo($message->getTo());
        }

        if (null !== $message->getFrom()) {
            $this->setFrom($message->getFrom());
        }

        if (null !== $message->getCc()) {
            $this->setCc($message->getCc());
        }

        if (null !== $message->getBcc()) {
            $this->setBcc($message->getBcc());
        }

        if (null !== $message->getReplyTo()) {
            $this->setReplyTo($message->getReplyTo());
        }

        return $this;
    }

    /**
     * @param EmailManagerHistory $model
     * @return $this
     */
    public function hydrateByPropelModel(EmailManagerHistory $model)
    {
        $this
            ->setBody($model->getBody())
            ->setSubject($model->getSubject())
            ->setDate($model->getCreatedAt());

        $emails = EmailManagerHistoryEmailQuery::create()
            ->innerJoinEmailManagerEmail()
            ->withColumn(EmailManagerEmailTableMap::EMAIL, 'email')
            ->withColumn(EmailManagerEmailTableMap::NAME, 'name')
            ->findByHistoryId($model->getId());

        /** @var EmailManagerHistoryEmail $email */
        foreach ($emails as $email) {
            $emailText = $email->getVirtualColumn('email');
            $nameText = $email->getVirtualColumn('name');

            switch ($email->getType()) {
                case 'to':
                    $this->to[$emailText] = $nameText;
                    break;
                case 'from':
                    $this->from[$emailText] = $nameText;
                    break;
                case 'cc':
                    $this->cc[$emailText] = $nameText;
                    break;
                case 'bcc':
                    $this->bcc[$emailText] = $nameText;
                    break;
                case 'rt':
                    $this->replyTo[$emailText] = $nameText;
                    break;
            }
        }

        return $this;
    }
}
