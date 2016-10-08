<?php

namespace TheliaMailManager\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use TheliaMailManager\TheliaMailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaMailManager::ROUTER;

    public function listAction(Request $request)
    {
        return $this->render('TheliaMailManager/traces');
    }

    public function viwAction(Request $request, $traceId)
    {
        return $this->render('TheliaMailManager/traceEdit', ['traceId' => $traceId]);
    }

    public function updateAction(Request $request, $traceId)
    {
        try {


            return $this->generateRedirectFromRoute(
                'admin_mail_manager_trace_view',
                [],
                ['traceId' => $traceId]
            );
        } catch (\Exception $e) {
            return $this->render('TheliaMailManager/traceEdit', ['traceId' => $traceId]);
        }
    }
}
