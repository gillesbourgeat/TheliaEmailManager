<?php

namespace TheliaEmailManager\Controller\Back;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use TheliaEmailManager\Event\TraceEvent;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Form\Forms;
use TheliaEmailManager\Form\TraceForm;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;
use TheliaEmailManager\TheliaEmailManager;
use TheliaEmailManager\Util\I18nTrait;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TraceController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaEmailManager::ROUTER;

    public function listAction(Request $request)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_TRACE, null, AccessManager::VIEW)) {
            return $response;
        }

        return $this->render('TheliaEmailManager/traces', [
            'traces' => EmailManagerTraceQuery::create()->find()
        ]);
    }

    public function viewAction(Request $request, $traceId)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_TRACE, null, AccessManager::VIEW)) {
            return $response;
        }

        $query = EmailManagerTraceQuery::create();

        I18nTrait::buildCriteriaI18n(
            $query,
            $request->getSession()->getAdminEditionLang()->getLocale(),
            ['TITLE', 'DESCRIPTION']
        );

        if (null === $trace = $trace = $query->findOneById($traceId)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(Forms::TRACE_UPDATE, 'form', [
            TraceForm::FIELD_ID => $trace->getId(),
            TraceForm::FIELD_TITLE => $trace->getVirtualColumn('i18n_TITLE'),
            TraceForm::FIELD_DESCRIPTION => $trace->getVirtualColumn('i18n_DESCRIPTION'),
            TraceForm::FIELD_DISABLE_HISTORY => $trace->getDisableHistory(),
            TraceForm::FIELD_DISABLE_SENDING => $trace->getDisableSending(),
            TraceForm::FIELD_FORCE_SAME_CUSTOMER_DISABLE => $trace->getForceSameCustomerDisable(),
            TraceForm::FIELD_EMAIL_BCC => $trace->getEmailBcc(),
            TraceForm::FIELD_EMAIL_REDIRECT => $trace->getEmailRedirect()
        ]);

        $this->getParserContext()->addForm($form);

        return $this->render('TheliaEmailManager/traceEdit', [
            'trace' => $trace
        ]);
    }

    public function updateAction(Request $request, $traceId)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_TRACE, null, AccessManager::UPDATE)) {
            return $response;
        }

        /** @var EmailManagerTrace $trace */
        if (null === $trace = EmailManagerTraceQuery::create()->findOneById($traceId)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(Forms::TRACE_UPDATE);

        try {
            $formUpdate = $this->validateForm($form);

            $trace
                ->setDisableHistory($formUpdate->get(TraceForm::FIELD_DISABLE_HISTORY)->getData())
                ->setDisableSending($formUpdate->get(TraceForm::FIELD_DISABLE_SENDING)->getData())
                ->setForceSameCustomerDisable($formUpdate->get(TraceForm::FIELD_FORCE_SAME_CUSTOMER_DISABLE)->getData())
                ->setEmailBcc($formUpdate->get(TraceForm::FIELD_EMAIL_BCC)->getData())
                ->setEmailRedirect($formUpdate->get(TraceForm::FIELD_EMAIL_REDIRECT)->getData())
                ->setLocale($formUpdate->get(TraceForm::FIELD_LOCALE)->getData())
                ->setTitle($formUpdate->get(TraceForm::FIELD_TITLE)->getData())
                ->setDescription($formUpdate->get(TraceForm::FIELD_DESCRIPTION)->getData())
            ;

            $this->getDispatcher()->dispatch(Events::TRACE_UPDATE, new TraceEvent($trace));

            $this->getParserContext()->clearForm($form);

            return $this->generateRedirectFromRoute(
                'admin_email_manager_trace_view',
                [],
                ['traceId' => $traceId]
            );
        } catch (\Exception $e) {
            $form->setErrorMessage($e->getMessage());
            $this->getParserContext()->addForm($form);

            return $this->render('TheliaEmailManager/traceEdit', ['traceId' => $traceId]);
        }
    }
}
