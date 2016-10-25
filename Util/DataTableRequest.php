<?php

namespace TheliaEmailManager\Util;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class DataTableRequest
{
    /** @var Request */
    protected $request;

    /** @var string[ */
    protected $columnsName;

    /**
     * DataTableRequest constructor.
     * @param Request $request
     * @param array $columnsName
     */
    public function __construct(Request $request, array $columnsName)
    {
        $this->request = $request;
        $this->columnsName = $columnsName;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        $perPage = (int) $this->request->query->get('length');

        return !in_array($perPage, [10, 25, 50, 100]) ? 25 : $perPage;
    }

    /**
     * @return float
     */
    public function getPage()
    {
        $start = (int) $this->request->query->get('start');

        return (int) $start / $this->getPerPage() + 1;
    }

    /**
     * @return int|null
     */
    public function getDraw()
    {
        $draw = $this->request->query->get('start', null);

        if ($draw !== null) {
            return (int) $draw;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getSearchValue()
    {
        $search = $this->request->query->get('search');

        return !empty($search['value']) ? $search['value'] : null;
    }

    public function getColumns()
    {
        return (array) $this->request->query->get('columns');
    }

    public function getColumnName($n)
    {
        return $this->columnsName[$n];
    }

    public function getOrderBy()
    {
        $n = $this->request->query->get('order')[0]['column'];

        return $this->getColumnName($n);
    }

    public function getOrder()
    {
        return $this->request->query->get('order')[0]['dir'] === 'asc' ? 'ASC' : 'DESC';
    }
}
