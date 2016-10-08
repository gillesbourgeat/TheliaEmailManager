<?php

namespace TheliaMailManager\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use TheliaMailManager\TheliaMailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class ConfigController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaMailManager::ROUTER;

    public function viwAction(Request $request)
    {
        return $this->render('TheliaMailManager/configuration');
    }

    public function updateAction(Request $request)
    {
        try {


            return $this->generateRedirectFromRoute(
                'admin_mail_manager_config'
            );
        } catch (\Exception $e) {
            return $this->render('TheliaMailManager/configuration');
        }
    }
}
