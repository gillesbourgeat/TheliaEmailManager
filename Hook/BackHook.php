<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Hook;

use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\CustomerQuery;
use TheliaEmailManager\Model\EmailManagerEmailQuery;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class BackHook extends BaseHook
{
    /** @var  RequestStack */
    protected $requestStack;

    /**
     * MenuHook constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param HookRenderEvent $event
     */
    public function onCustomerEditTop(HookRenderEvent $event)
    {
        $customer = CustomerQuery::create()->findOneById((int) $event->getArgument('customer_id'));

        $event->add(
            $this->render("TheliaEmailManager/hook/customer-edit.top.html", [
                'emailManagerEmail' => EmailManagerEmailQuery::create()
                    ->filterByEmail($customer->getEmail())
                    ->findOne()
            ])
        );
    }

    /**
     * @param HookRenderEvent $event
     */
    public function onCustomerEditBottom(HookRenderEvent $event)
    {
        $customer = CustomerQuery::create()->findOneById((int) $event->getArgument('customer_id'));

        $event->add(
            $this->render("TheliaEmailManager/hook/customer-edit.bottom.html", [
                'emailManagerEmail' => EmailManagerEmailQuery::create()
                    ->filterByEmail($customer->getEmail())
                    ->findOne()
            ])
        );
    }
}
