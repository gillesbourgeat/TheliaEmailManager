<?php

namespace TheliaEmailManager\Controller\Back;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerEmailQuery;
use TheliaEmailManager\Model\Map\EmailManagerEmailTableMap;
use TheliaEmailManager\Request\DataTableRequest;
use TheliaEmailManager\Response\DataTableResponse;
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
                EmailManagerEmailTableMap::EMAIL
            ]
        );

        $response = DataTableResponse::create()
            ->setDraw($dataTableRequest->getDraw())
            ->setRecordsFiltered(EmailManagerEmailQuery::create()->count());

        $query = EmailManagerEmailQuery::create();

        if (null !== $search = $dataTableRequest->getSearchValue()) {
            $query
                ->filterByEmail('%' . $search . '%', Criteria::LIKE)
                ->_or()
                ->filterByName('%' . $search . '%', Criteria::LIKE);
        }

        $response->setRecordsTotal($query->count());

        $query->orderBy(
            $dataTableRequest->getOrderBy(),
            $dataTableRequest->getOrder()
        );

        $emails = $query->paginate(
            $dataTableRequest->getPage(),
            $dataTableRequest->getPerPage()
        );

        /** @var EmailManagerEmail $email */
        foreach ($emails as $email) {
            $response->addData([
                $email->getId(),
                $email->getName(),
                $email->getEmail()
            ]);
        }

        return $response;
    }
}
