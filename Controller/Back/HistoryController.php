<?php

namespace TheliaEmailManager\Controller\Back;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class HistoryController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaEmailManager::ROUTER;

    public function listAction(Request $request)
    {
        return $this->render('TheliaEmailManager/histories');
    }

    public function viwAction(Request $request, $historyId)
    {
        return $this->render('TheliaEmailManager/modal/history', ['historyId' => $historyId]);
    }

    public function resendAction(Request $request, $historyId)
    {

    }
}
