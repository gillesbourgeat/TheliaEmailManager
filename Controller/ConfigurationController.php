<?php

namespace TheliaEmailManager\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use TheliaEmailManager\Form\Forms;
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
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_CONFIGURATION, null, AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->createForm(Forms::CONFIGURATION, 'form', [
            TheliaEmailManager::CONFIG_DISABLE_SENDING => TheliaEmailManager::getDisableSending(),
            TheliaEmailManager::CONFIG_ENABLE_HISTORY => TheliaEmailManager::getEnableHistory(),
            TheliaEmailManager::CONFIG_REDIRECT_ALL_TO => TheliaEmailManager::getRedirectAllTo()
        ]);

        $this->getParserContext()->addForm($form);

        return $this->render('TheliaEmailManager/configuration');
    }

    public function updateAction(Request $request)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_CONFIGURATION, null, AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(Forms::CONFIGURATION);

        try {
            $configurationForm = $this->validateForm($form);

            TheliaEmailManager::setDisableSending(
                $configurationForm->get(TheliaEmailManager::CONFIG_DISABLE_SENDING)->getData()
            );
            TheliaEmailManager::setEnableHistory(
                $configurationForm->get(TheliaEmailManager::CONFIG_ENABLE_HISTORY)->getData()
            );
            TheliaEmailManager::setRedirectAllTo(
                $configurationForm->get(TheliaEmailManager::CONFIG_REDIRECT_ALL_TO)->getData()
            );

            return $this->generateRedirectFromRoute(
                'admin_email_manager_configuration'
            );
        } catch (\Exception $e) {
            $form->setErrorMessage($e->getMessage());
            $this->getParserContext()->addForm($form);
            return $this->render('TheliaEmailManager/configuration');
        }
    }
}
