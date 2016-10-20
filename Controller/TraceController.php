<?php

namespace TheliaEmailManager\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use TheliaEmailManager\Model\EmailManagerTraceQuery;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaEmailManager::ROUTER;

    public function listAction(Request $request)
    {
        return $this->render('TheliaEmailManager/traces', [
            'traces' => EmailManagerTraceQuery::create()->find()
        ]);
    }

    public function viewAction(Request $request, $traceId)
    {
        return $this->render('TheliaEmailManager/traceEdit', [
            'trace' => EmailManagerTraceQuery::create()->findOneById($traceId)
        ]);
    }

    public function updateAction(Request $request, $traceId)
    {
        try {
            $trace = EmailManagerTraceQuery::create()->findOneById($traceId);

            return $this->generateRedirectFromRoute(
                'admin_email_manager_trace_view',
                [],
                ['traceId' => $traceId]
            );
        } catch (\Exception $e) {
            return $this->render('TheliaEmailManager/traceEdit', ['traceId' => $traceId]);
        }
    }
}
