<?php

namespace TheliaEmailManager\Service;

use Symfony\Component\Routing\Router;
use Thelia\Model\AdminQuery;
use Thelia\Model\CustomerQuery;
use Thelia\Tools\URL;
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
    /** @var Router */
    protected $router;

    /** @var EmailManagerEmail[] */
    protected $emailManagerEmailCache = [];

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
            ->setDisableSend(true)
            ->save();

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
        if (!EmailUtil::checkMailStructure($email)) {
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
            $model->save();
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
