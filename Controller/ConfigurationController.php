<?php

namespace TheliaEmailManager\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class ConfigurationController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaEmailManager::ROUTER;

    public function viewAction(Request $request)
    {
        return $this->render('TheliaEmailManager/configuration');
    }

    public function updateAction(Request $request)
    {
        try {


            return $this->generateRedirectFromRoute(
                'admin_email_manager_config'
            );
        } catch (\Exception $e) {
            return $this->render('TheliaEmailManager/configuration');
        }
    }
}
