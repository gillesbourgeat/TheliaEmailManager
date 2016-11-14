<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Controller\Front;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Request;
use TheliaEmailManager\Exception\InvalidHashException;
use TheliaEmailManager\Form\Forms;
use TheliaEmailManager\Model\EmailManagerEmailQuery;
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
        $templateData = [];
        $status = 200;

        try {
            if (null === $model = EmailManagerEmailQuery::create()->findOneByDisableHash($hash)) {
                throw new InvalidHashException();
            }

            $templateData['email'] = $model->getEmail();

            if ($model->getDisableSend()) {
                $templateData['success'] = true;
            } else {
                $templateData['displayForm'] = true;
            }
        } catch (InvalidHashException $e) {
            $status = 400;
            $templateData['success'] = false;
        }

        return $this->render('TheliaEmailManager/disableConfirmation', $templateData, $status);
    }

    public function disableEmailConfirmationAction(Request $request, $hash)
    {
        /** @var EmailService $emailService */
        $emailService = $this->getContainer()->get('thelia.email.manager.email.service');

        $templateData = [];
        $status = 200;

        $form = $this->createForm(Forms::DISABLE_EMAIL_CONFIRMATION);

        try {
            $this->validateForm($form);

            $model = $emailService->disableSendingEmailByHash($hash);

            $templateData['success'] = true;
            $templateData['email'] = $model->getEmail();
        } catch (InvalidHashException $e) {
            $status = 400;
            $templateData['success'] = false;
        } catch (\Exception $e) {
            $status = 400;
            $templateData['error'] = $e->getMessage();
        }

        return $this->render('TheliaEmailManager/disableConfirmation', $templateData, $status);
    }
}
