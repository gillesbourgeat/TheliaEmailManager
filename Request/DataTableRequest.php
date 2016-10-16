<?php

namespace TheliaEmailManager\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class DataTableRequest
{
    /** @var Request */
    protected $request;

    /**
     * DataTableRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
}
