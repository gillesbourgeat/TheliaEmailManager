<?php

namespace TheliaEmailManager\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class DataTableResponse extends JsonResponse
{
    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return DataTableResponse
     */
    public static function create($data = [], $status = 200, $headers = array())
    {
        return parent::create([
            'draw' => 0,
            'data' => [],
            'recordsTotal' => 0,
            'recordsFiltered' => 0
        ], $status, $headers);
    }

    public function __construct($data, $status, array $headers)
    {
        parent::__construct([
            'draw' => 0,
            'data' => [],
            'recordsTotal' => 0,
            'recordsFiltered' => 0
        ], $status, $headers);
    }

    public function setData($data = [])
    {
        $this->data['data'] = $data;
        return $this;
    }

    public function addData($data = [])
    {
        $this->data['data'][] = $data;
        return $this;
    }

    public function setDraw($draw)
    {
        $this->data['draw'] = $draw;
        return $this;
    }

    public function setRecordsTotal($recordsTotal)
    {
        $this->data['recordsTotal'] = $recordsTotal;
        return $this;
    }

    public function setRecordsFiltered($recordsFiltered)
    {
        $this->data['recordsFiltered'] = $recordsFiltered;
        return $this;
    }
}
