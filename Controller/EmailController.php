<?php

namespace TheliaEmailManager\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Request;
use TheliaEmailManager\Exception\InvalidHashException;
use TheliaEmailManager\Service\EmailService;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailController extends BaseFrontController
{
    /** @var string */
    protected $currentRouter = TheliaEmailManager::ROUTER;

    public function disableEmailAction(Request $request, $hash)
    {
        /** @var EmailService $emailService */
        $emailService = $this->getContainer()->get('thelia.email.manager.email.service');

        $templateData = [];
        $status = 200;

        try {
            $model = $emailService->disableSendingEmailByHash($hash);

            $templateData['success'] = true;
            $templateData['email'] = $model->getEmail();
        } catch (InvalidHashException $e) {
            $status = 400;
            $templateData['success'] = false;
        }

        return $this->render('TheliaEmailManager/disableConfirmation', $templateData, $status);
    }
}
