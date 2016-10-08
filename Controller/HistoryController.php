<?php

namespace TheliaMailManager\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use TheliaMailManager\TheliaMailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class HistoryController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaMailManager::ROUTER;

    public function listAction(Request $request)
    {
        return $this->render('TheliaMailManager/histories');
    }

    public function viwAction(Request $request, $historyId)
    {
        return $this->render('TheliaMailManager/modal/history', ['historyId' => $historyId]);
    }

    public function resendAction(Request $request, $historyId)
    {

    }
}
