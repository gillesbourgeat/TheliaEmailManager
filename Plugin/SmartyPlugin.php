<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Plugin;

use Thelia\Model\CustomerQuery;
use TheliaEmailManager\Service\EmailService;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SmartyPlugin extends AbstractSmartyPlugin
{
    /** @var EmailService */
    protected $emailService;

    /**
     * SmartyPlugin constructor.
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * require param email or customerId
     *
     * @param array $params
     * @return string
     */
    public function disableUrl(array $params)
    {
        if (null !== $email =  $this->getParam($params, 'email')) {
            return $this->emailService->getDisableUrl($email);
        }

        if (null !== $customerId =  $this->getParam($params, 'customer_id')) {
            if (null !== $customer = CustomerQuery::create()->findOneByEmail($customerId)) {
                return $this->emailService->getDisableUrl($customer->getEmail());
            }
        }

        throw new \InvalidArgumentException('Invalid argument email or customer_id for the smarty function email_manager_disable_url');
    }

    /**
     * @return SmartyPluginDescriptor[]
     */
    public function getPluginDescriptors()
    {
        return array(
            new SmartyPluginDescriptor("function", "email_manager_disable_url", $this, "disableUrl")
        );
    }
}
