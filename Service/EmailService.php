<?php

namespace TheliaEmailManager\Service;

use Symfony\Component\Routing\Router;
use Symfony\Component\Validator\Constraints\Date;
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
        if (!EmailUtil::checkMailStructure($email)) {
            throw new InvalidEmailException("Invalid email : " . $email);
        }

        if (null === $model = EmailManagerEmailQuery::create()->findOneByEmail($email)) {
            $model = new EmailManagerEmail();
            $model->setEmail($email);
            $model->save();
            $this->generateDisableUrl($model);
        }

        return URL::getInstance()->absoluteUrl(
            $this->router->generate(
                'email_manager_disable_sending',
                ['hash' => $model->getDisableHash()]
            )
        );
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
