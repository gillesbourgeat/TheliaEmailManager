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
use Symfony\Component\Routing\RouterInterface;
use Thelia\Model\AdminQuery;
use Thelia\Model\CustomerQuery;
use Thelia\Tools\URL;
use TheliaEmailManager\Event\EmailEvent;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Exception\InvalidEmailException;
use TheliaEmailManager\Exception\InvalidHashException;
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerEmailQuery;
use TheliaEmailManager\Util\EmailUtil;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailService
{
    /** @var RouterInterface */
    protected $router;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var EmailManagerEmail[] */
    protected $emailManagerEmailCache = [];

    /**
     * @param RouterInterface $router
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(RouterInterface $router, EventDispatcherInterface $eventDispatcher)
    {
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * For disable sending email to customer thanks to the hash
     * @param string $hash
     * @throws InvalidHashException
     * @return EmailManagerEmail
     */
    public function disableSendingEmailByHash($hash)
    {
        if (null === $model = EmailManagerEmailQuery::create()->findOneByDisableHash($hash)) {
            throw new InvalidHashException();
        }

        $model
            ->setDisableSendDate(new \DateTime())
            ->setDisableSend(true);

        $this->eventDispatcher->dispatch(
            Events::EMAIL_UPDATE,
            new EmailEvent($model)
        );

        return $model;
    }

    /**
     * @param string $email
     * @return string url for disable sending email
     * @throws InvalidEmailException
     */
    public function getDisableUrl($email)
    {
        $model = $this->getEmailManagerEmail($email);

        return URL::getInstance()->absoluteUrl(
            $this->router->generate(
                'email_manager_disable_sending',
                ['hash' => $model->getDisableHash()]
            )
        );
    }

    /**
     * @param string $email
     * @param string|null $name
     * @param bool $force pass to true for ignore cache
     * @return EmailManagerEmail
     * @throws InvalidEmailException
     */
    public function getEmailManagerEmail($email, $name = null, $force = false)
    {
        if (!EmailUtil::checkEmailStructure($email)) {
            throw new InvalidEmailException("Invalid email : " . $email);
        }

        if (isset($this->emailManagerEmailCache[$email]) && !$force) {
            return $this->emailManagerEmailCache[$email];
        }

        if (null === $model = EmailManagerEmailQuery::create()->findOneByEmail($email)) {
            if (null !== $customer = CustomerQuery::create()->findOneByEmail($email)) {
                $name = ucfirst($customer->getFirstname()) . ' ' . ucfirst($customer->getLastname());
            } elseif (null !== $admin = AdminQuery::create()->findOneByEmail($email)) {
                $name = ucfirst($admin->getFirstname()) . ' ' . ucfirst($admin->getLastname());
            }

            $model = (new EmailManagerEmail())
                ->setEmail($email)
                ->setName($name);
            $this->generateDisableUrl($model);

            $this->eventDispatcher->dispatch(
                Events::EMAIL_CREATE,
                new EmailEvent($model)
            );
        }

        if ($name !== null && !empty($name) && $model->getName() != $name) {
            $model->setName($name);

            $this->eventDispatcher->dispatch(
                Events::EMAIL_UPDATE,
                new EmailEvent($model)
            );
        }

        $this->emailManagerEmailCache[$email] = $model;

        return $this->emailManagerEmailCache[$email];
    }

    /**
     * @param EmailManagerEmail $model
     */
    protected function generateDisableUrl(EmailManagerEmail $model)
    {
        if (empty($model->getEmail())) {
            throw new \InvalidArgumentException("The EmailManagerEmail object must content an email");
        }

        $key = md5($model->getEmail()) . uniqid(true);
        $model->setDisableHash(hash('sha256', $key));
    }
}
