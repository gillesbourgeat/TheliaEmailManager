<?php

namespace TheliaEmailManager\Controller\Back;

use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use Thelia\Model\Tools\ModelCriteriaTools;
use TheliaEmailManager\Model\EmailManagerEmailQuery;
use TheliaEmailManager\Model\EmailManagerHistory;
use TheliaEmailManager\Model\EmailManagerHistoryEmail;
use TheliaEmailManager\Model\EmailManagerHistoryEmailQuery;
use TheliaEmailManager\Model\EmailManagerHistoryQuery;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;
use TheliaEmailManager\Model\Map\EmailManagerEmailTableMap;
use TheliaEmailManager\Model\Map\EmailManagerHistoryEmailTableMap;
use TheliaEmailManager\Model\Map\EmailManagerHistoryTableMap;
use TheliaEmailManager\TheliaEmailManager;
use TheliaEmailManager\Util\DataTableRequest;
use TheliaEmailManager\Util\DataTableResponse;
use TheliaEmailManager\Util\I18nTrait;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class HistoryController extends BaseAdminController
{
    /** @var string */
    protected $currentRouter = TheliaEmailManager::ROUTER;

    public function listAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->ajaxListAction($request);
        }

        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_HISTORY, null, AccessManager::VIEW)) {
            return $response;
        }

        $emailManagerTraces = EmailManagerTraceQuery::create();

        I18nTrait::buildCriteriaI18n(
            $emailManagerTraces,
            $request->getSession()->getAdminEditionLang()->getLocale(),
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
        return $this->render(
            'TheliaEmailManager/histories',
            [
                'traces' => $traces
            ]
        );
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
                EmailManagerHistoryTableMap::ID,
                EmailManagerHistoryTableMap::TRACE_ID,
                EmailManagerHistoryTableMap::CREATED_AT,
                EmailManagerHistoryTableMap::SUBJECT
            ]
        );

        $dataTableResponse = (new DataTableResponse)
            ->setDraw($dataTableRequest->getDraw())
            ->setRecordsTotal(EmailManagerHistoryQuery::create()->count());

        $query = EmailManagerHistoryQuery::create();

        // search
        $this->emailManagerHistoryQueryFilter($query, $dataTableRequest);

        // first query for count without pagination
        $dataTableResponse->setRecordsFiltered($query->count());

        // order
        $query->orderBy(
            $dataTableRequest->getOrderBy(),
            $dataTableRequest->getOrder()
        );

        // pagination
        $histories = $query->paginate(
            $dataTableRequest->getPage(),
            $dataTableRequest->getPerPage()
        );

        $historyIds = [];

        /** @var EmailManagerHistory $history */
        foreach ($histories as $history) {
            $historyIds[] = $history->getId();
        }

        $traceIds = [];
        /** @var EmailManagerHistory $history */
        foreach ($histories as $history) {
            $traceIds[] = $history->getTraceId();
        }

        $emailManagerTraces = EmailManagerTraceQuery::create();

        I18nTrait::buildCriteriaI18n(
            $emailManagerTraces,
            $request->getSession()->getAdminEditionLang()->getLocale(),
            ['TITLE']
        );

        $traces = [];
        /** @var EmailManagerTrace $emailManagerTrace */
        foreach ($emailManagerTraces->findById(array_unique($traceIds)) as $emailManagerTrace) {
             $traces[$emailManagerTrace->getId()] = [
                 'id' => $emailManagerTrace->getId(),
                 'title' => $emailManagerTrace->getVirtualColumn('i18n_TITLE'),
                 'url' => $this->retrieveUrlFromRouteId(
                     'admin_email_manager_trace_view',
                     [],
                     ['traceId' => $emailManagerTrace->getId()]
                 )
             ];
        }

        $emailManagerHistoryEmails = EmailManagerHistoryEmailQuery::create()
            ->innerJoinEmailManagerEmail(EmailManagerEmailTableMap::TABLE_NAME)
            ->withColumn(EmailManagerEmailTableMap::NAME, 'Name')
            ->withColumn(EmailManagerEmailTableMap::EMAIL, 'Email')
            ->findByHistoryId(array_unique($historyIds))->toArray('id');

        $historyEmails = [];
        /** @var EmailManagerHistoryEmail $emailManagerHistoryEmail */
        foreach ($emailManagerHistoryEmails as $emailManagerHistoryEmail) {
            $historyEmails[$emailManagerHistoryEmail['HistoryId']][] = [
                'name' => $emailManagerHistoryEmail['Name'],
                'email' => $emailManagerHistoryEmail['Email'],
                'type' => $emailManagerHistoryEmail['Type'],
            ];
        }

        /** @var EmailManagerHistory $history */
        foreach ($histories as $history) {
            $dataTableResponse->addData([
                $history->getId(),
                $traces[$history->getTraceId()],
                $history->getCreatedAt($request->getSession()->getLang()->getDateFormat() . ' ' . $request->getSession()->getLang()->getTimeFormat()),
                $history->getSubject(),
                $historyEmails[$history->getId()]
            ]);
        }

        return new JsonResponse($dataTableResponse->getData());
    }

    public function viwAction(Request $request, $historyId)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_HISTORY, null, AccessManager::VIEW)) {
            return $response;
        }

        return $this->render('TheliaEmailManager/modal/history', ['historyId' => $historyId]);
    }

    public function resendAction(Request $request, $historyId)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(TheliaEmailManager::RESOURCE_HISTORY, null, AccessManager::UPDATE)) {
            return $response;
        }
    }

    /**
     * @param EmailManagerHistoryQuery $query
     * @param DataTableRequest $dataTableRequest
     */
    protected function emailManagerHistoryQueryFilter(
        EmailManagerHistoryQuery $query,
        DataTableRequest $dataTableRequest
    ) {
        if (null !== $search = $dataTableRequest->getColumns()->getByName('trace')->getSearchValue()) {
            $query->filterByTraceId($search);
        }

        if (null !== $search = $dataTableRequest->getColumns()->getByName('subject')->getSearchValue()) {
            $query->filterBySubject('%' . $search . '%', Criteria::LIKE);
        }

        if (null !== $search = $dataTableRequest->getColumns()->getByName('date')->getSearchValue()) {
            $query->filterByCreatedAt([
                'min' => $search . ' 00:00:00',
                'max' =>  $search . ' 23:59:59'
            ]);
        }

        if (null !== $search = $dataTableRequest->getColumns()->getByName('emails')->getSearchValue()) {
            $query->useEmailManagerHistoryEmailQuery()
                ->useEmailManagerEmailQuery()
                ->filterByEmail('%' . $search . '%', Criteria::LIKE)
                ->_or()
                ->filterByName('%' . $search . '%', Criteria::LIKE)
                ->endUse()
                ->endUse();
        }
    }
}
