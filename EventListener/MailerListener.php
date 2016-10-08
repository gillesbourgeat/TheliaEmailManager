<?php

namespace TheliaEmailManager\EventListener;

use TheliaEmailManager\Plugin\SwiftEventListenerPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\MailTransporterEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\ConfigQuery;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class MailerListener implements EventSubscriberInterface
{
    /**
     * Add SwiftMail Plugin event listener
     *
     * @param MailTransporterEvent $event
     * @param string $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function addPlugin(MailTransporterEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        if (!$event->hasTransporter()) {
            $event->setMailerTransporter(
                ConfigQuery::isSmtpEnable() ? $this->configureSmtp() : \Swift_emailTransport::newInstance()
            );
        }

        /** @var MailerFactory $mailer */
        $event->getTransporter()->registerPlugin(
            new SwiftEventListenerPlugin($dispatcher)
        );
    }

    /**
     * @return \Swift_SmtpTransport
     */
    protected function configureSmtp()
    {
        $smtpTransporter = \Swift_SmtpTransport::newInstance(ConfigQuery::getSmtpHost(), ConfigQuery::getSmtpPort());

        if (ConfigQuery::getSmtpEncryption()) {
            $smtpTransporter->setEncryption(ConfigQuery::getSmtpEncryption());
        }
        if (ConfigQuery::getSmtpUsername()) {
            $smtpTransporter->setUsername(ConfigQuery::getSmtpUsername());
        }
        if (ConfigQuery::getSmtpPassword()) {
            $smtpTransporter->setPassword(ConfigQuery::getSmtpPassword());
        }
        if (ConfigQuery::getSmtpAuthMode()) {
            $smtpTransporter->setAuthMode(ConfigQuery::getSmtpAuthMode());
        }
        if (ConfigQuery::getSmtpTimeout()) {
            $smtpTransporter->setTimeout(ConfigQuery::getSmtpTimeout());
        }
        if (ConfigQuery::getSmtpSourceIp()) {
            $smtpTransporter->setSourceIp(ConfigQuery::getSmtpSourceIp());
        }

        return $smtpTransporter;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::MAILTRANSPORTER_CONFIG => ['addPlugin', 128]
        ];
    }
}
