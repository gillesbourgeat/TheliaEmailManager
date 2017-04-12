<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $query = EmailManagerTraceQuery::create()->filterByParentId(null);

        I18nTrait::buildCriteriaI18n(
            $query,
            $request->getSession()->getLang()->getLocale(),
            ['TITLE', 'DESCRIPTION']
        );

        $emailManagerTraces = $query->find();

        $traces = [];

        /** @var EmailManagerTrace $emailManagerTrace */
        foreach ($emailManagerTraces as $emailManagerTrace) {
            $query = EmailManagerTraceQuery::create()->filterByParentId($emailManagerTrace->getId());

            I18nTrait::buildCriteriaI18n(
                $query,
                $request->getSession()->getLang()->getLocale(),
                ['TITLE', 'DESCRIPTION']
            );

            $emailManagerTraceChildren = $query->find();

            $traces[] = [
                'parent' => $emailManagerTrace,
                'children' => $emailManagerTraceChildren
            ];
        }

        return $this->render('TheliaEmailManager/traces', [
            'traces' => $traces
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
            $this->getCurrentEditionLang()->getLocale(),
            ['TITLE', 'DESCRIPTION']
        );

        if (null === $trace = $query->findOneById($traceId)) {
            throw new NotFoundHttpException();
        }

        if ($trace->getParentId()) {
            $parent = EmailManagerTraceQuery::create()->findOneById($trace->getParentId());

            $form = $this->createForm(Forms::TRACE_UPDATE, 'form', [
                TraceForm::FIELD_ID => $trace->getId(),
                TraceForm::FIELD_TITLE => $trace->getVirtualColumn('i18n_TITLE'),
                TraceForm::FIELD_DESCRIPTION => $trace->getVirtualColumn('i18n_DESCRIPTION'),
                TraceForm::FIELD_DISABLE_HISTORY => $parent->getDisableHistory(),
                TraceForm::FIELD_DISABLE_SENDING => $parent->getDisableSending(),
                TraceForm::FIELD_FORCE_SAME_CUSTOMER_DISABLE => $parent->getForceSameCustomerDisable(),
                TraceForm::FIELD_EMAIL_BCC => $parent->getEmailBcc(),
                TraceForm::FIELD_EMAIL_REDIRECT => $parent->getEmailRedirect()
            ]);
        } else {
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
        }

        $this->getParserContext()->addForm($form);

        $emailManagerTraces = EmailManagerTraceQuery::create();

        I18nTrait::buildCriteriaI18n(
            $emailManagerTraces,
            $request->getSession()->getLang()->getLocale(),
            ['TITLE']
        );

        $traces = [];
        /** @var EmailManagerTrace $emailManagerTrace */
        foreach ($emailManagerTraces->filterByParentId(null)->find() as $emailManagerTrace) {
            $traces[$emailManagerTrace->getId()] = [
                'id' => $emailManagerTrace->getId(),
                'title' => $emailManagerTrace->getVirtualColumn('i18n_TITLE')
            ];
        }

        $childrenTraces = EmailManagerTraceQuery::create();

        I18nTrait::buildCriteriaI18n(
            $childrenTraces,
            $request->getSession()->getLang()->getLocale(),
            ['TITLE']
        );

        return $this->render('TheliaEmailManager/traceEdit', [
            'trace' => $trace,
            'traces' => $traces,
            'childrenTraces' => $childrenTraces->findByParentId($trace->getId())
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

            if (!$trace->getParentId()) {
                $trace
                    ->setDisableHistory($formUpdate->get(TraceForm::FIELD_DISABLE_HISTORY)->getData())
                    ->setDisableSending($formUpdate->get(TraceForm::FIELD_DISABLE_SENDING)->getData())
                    ->setForceSameCustomerDisable($formUpdate->get(TraceForm::FIELD_FORCE_SAME_CUSTOMER_DISABLE)->getData())
                    ->setEmailBcc($formUpdate->get(TraceForm::FIELD_EMAIL_BCC)->getData())
                    ->setEmailRedirect($formUpdate->get(TraceForm::FIELD_EMAIL_REDIRECT)->getData());
            }

            $trace
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

            return $this->viewAction($request, $traceId);
        }
    }

    public function unlinkAction(Request $request, $traceId)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_TRACE, null, AccessManager::UPDATE)) {
            return $response;
        }

        /** @var EmailManagerTrace $trace */
        if (null === $trace = EmailManagerTraceQuery::create()->findOneById($traceId)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(Forms::GENERIC);

        try {
            $this->validateForm($form);

            $this->getDispatcher()->dispatch(Events::TRACE_UNLINK, new TraceEvent($trace));

            return $this->generateRedirectFromRoute(
                'admin_email_manager_trace_view',
                [],
                ['traceId' => $traceId]
            );
        } catch (\Exception $e) {
            $form->setErrorMessage($e->getMessage());
            $this->getParserContext()->addForm($form);

            return $this->viewAction($request, $traceId);
        }
    }
}
