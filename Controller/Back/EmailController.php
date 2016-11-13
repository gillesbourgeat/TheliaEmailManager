<?php

namespace TheliaEmailManager\Controller\Back;

use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use TheliaEmailManager\Event\EmailEvent;
use TheliaEmailManager\Event\Events;
use TheliaEmailManager\Form\Forms;
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerEmailQuery;
use TheliaEmailManager\Model\Map\EmailManagerEmailTableMap;
use TheliaEmailManager\Util\DataTableRequest;
use TheliaEmailManager\Util\DataTableResponse;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaEmailManager::ROUTER;

    public function listAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->ajaxListAction($request);
        }

        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_EMAIL, null, AccessManager::VIEW)) {
            return $response;
        }

        return $this->render('TheliaEmailManager/emails');
    }

    public function ajaxListAction(Request $request)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_EMAIL, null, AccessManager::VIEW)) {
            return $response;
        }

        $dataTableRequest = new DataTableRequest(
            $request,
            [
                EmailManagerEmailTableMap::ID,
                EmailManagerEmailTableMap::NAME,
                EmailManagerEmailTableMap::EMAIL,
                EmailManagerEmailTableMap::DISABLE_SEND_DATE,
            ]
        );

        $dataTableResponse = (new DataTableResponse)
            ->setDraw($dataTableRequest->getDraw())
            ->setRecordsTotal(EmailManagerEmailQuery::create()->count());

        $query = EmailManagerEmailQuery::create();

        // search
        if (null !== $search = $dataTableRequest->getSearchValue()) {
            $query
                ->filterByEmail('%' . $search . '%', Criteria::LIKE)
                ->_or()
                ->filterByName('%' . $search . '%', Criteria::LIKE);
        }

        // first query for count without pagination
        $dataTableResponse->setRecordsFiltered($query->count());

        // order
        $query->orderBy(
            $dataTableRequest->getOrderBy(),
            $dataTableRequest->getOrder()
        );

        // pagination
        $emails = $query->paginate(
            $dataTableRequest->getPage(),
            $dataTableRequest->getPerPage()
        );

        /** @var EmailManagerEmail $email */
        foreach ($emails as $email) {
            $dataTableResponse->addData([
                $email->getId(),
                $email->getName(),
                $email->getEmail(),
                $email->getDisableSendDate($request->getSession()->getLang()->getDateFormat() . ' ' . $request->getSession()->getLang()->getTimeFormat())
            ]);
        }

        return new JsonResponse($dataTableResponse->getData());
    }

    public function reactivateAction(Request $request, $emailId)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_EMAIL, null, AccessManager::UPDATE)) {
            return $response;
        }

        /** @var EmailManagerEmail $email */
        if (null === $email = EmailManagerEmailQuery::create()->findOneById($emailId)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(Forms::GENERIC);

        try {
            $this->validateForm($form);

            $email
                ->setDisableSendDate(null)
                ->setDisableSend(false);

            $this->getDispatcher()->dispatch(Events::EMAIL_UPDATE, new EmailEvent($email));

            return $this->generateRedirectFromRoute(
                'admin_email_manager_email',
                []
            );
        } catch (\Exception $e) {
            $form->setErrorMessage($e->getMessage());
            $this->getParserContext()->addForm($form);

            return $this->listAction($request);
        }
    }
}
