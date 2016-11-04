<?php

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
}
