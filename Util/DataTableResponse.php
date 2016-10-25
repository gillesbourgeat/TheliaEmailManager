<?php

namespace TheliaEmailManager\Util;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class DataTableResponse
{
    protected $data = [
        'draw' => 0,
        'data' => [],
        'recordsTotal' => 0,
        'recordsFiltered' => 0
    ];

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
        $this->data['draw'] = (int) $draw;
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

    public function getData()
    {
        return $this->data;
    }
}
