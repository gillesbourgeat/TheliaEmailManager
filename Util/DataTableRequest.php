<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Util;

use Symfony\Component\HttpFoundation\Request;

class DataTableColumnCollection extends \ArrayObject
{
    /**
     * @param int $offset
     * @param DataTableColumn $value
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof DataTableColumn)) {
            throw new \InvalidArgumentException('Invalid argument value');
        }

        parent::offsetSet($offset, $value);
    }

    /**
     * @param string $name
     * @return DataTableColumn|null
     */
    public function getByName($name)
    {
        return $this[$name];
    }
}

class DataTableColumn
{
    /** @var string */
    protected $name;

    /** @var bool */
    protected $searchable;

    /** @var bool */
    protected $orderable;

    /** @var string */
    protected $searchValue;

    /** @var bool */
    protected $searchRegex;

    /**
     * DataTableColumn constructor.
     * @param array $column
     */
    public function __construct(array $column)
    {
        $this->name = $column['name'];
        $this->searchable = (bool) $column['searchable'];
        $this->orderable = (bool) $column['orderable'];
        $this->searchValue = empty($column['search']['value']) ? null : $column['search']['value'];
        $this->searchRegex = (bool) $column['search']['regex'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * @return boolean
     */
    public function isOrderable()
    {
        return $this->orderable;
    }

    /**
     * @return string
     */
    public function getSearchValue()
    {
        return $this->searchValue;
    }

    /**
     * @return boolean
     */
    public function isSearchRegex()
    {
        return $this->searchRegex;
    }
}

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class DataTableRequest
{
    /** @var Request */
    protected $request;

    /** @var string[ */
    protected $columnsName;

    /** @var DataTableColumnCollection */
    protected $columns;

    /** @var string */
    protected $searchValue;

    /** @var bool */
    protected $searchRegex;

    /** @var int */
    protected $start;

    /** @var int */
    protected $length;

    /** @var int */
    protected $draw;

    /** @var string ASC or DESC */
    protected $order;

    /** @var string */
    protected $orderBy;

    public function __construct(Request $request, array $columnsName)
    {
        $this->columnsName = $columnsName;
        $this->request = $request;

        $this->columns = new DataTableColumnCollection();

        $columns = $this->request->query->get('columns');

        foreach ($columns as $column) {
            $this->columns[empty($column['name']) ? null : $column['name']] = new DataTableColumn($column);
        }

        $search = $this->request->query->get('search');

        $this->searchValue = empty($search['value']) ? null : $search['value'];
        $this->searchRegex = (bool) $search['regex'];
        $this->start = (int) $this->request->query->get('start');
        $this->length = (int) $this->request->query->get('length');
        $this->draw = (int) $this->request->query->get('draw');
        $this->order = $this->request->query->get('order')[0]['dir'] === 'asc' ? 'ASC' : 'DESC';
        $this->orderBy = $this->columnsName[$this->request->query->get('order')[0]['column']];
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->getLength();
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return !in_array($this->length, [10, 25, 50, 100]) ? 25 : $this->length;
    }

    /**
     * @return float
     */
    public function getPage()
    {
        return (int) $this->start / $this->length + 1;
    }

    /**
     * @return int
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * @return bool
     */
    public function getSearchRegex()
    {
        return $this->searchRegex;
    }

    /**
     * @return string
     */
    public function getSearchValue()
    {
        return $this->searchValue;
    }

    /**
     * @return DataTableColumnCollection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * ASC or DESC
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }
}
