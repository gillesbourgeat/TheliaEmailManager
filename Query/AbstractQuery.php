<?php

namespace TheliaEmailManager\Query;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
abstract class AbstractQuery
{
    /** @var string[] */
    protected $columns = [];

    protected $query = (object) [
        'page' => 1,
        'perPage' => 30,
        'limit' => 0,
        'order' => 'id',
        'filterGroups' => []
    ];

    /**
     * @param int $page
     * @param int $perPage
     * @return $this
     */
    public function paginate($page, $perPage)
    {
        $this->query->page = (int) $page;
        $this->query->perPage = (int) $perPage;
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->query->limit = (int) $limit;
        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orderBy($column)
    {
        if (in_array($this->columns, $column)) {
            $this->query->order = $column;
            return $this;
        }

        throw new \InvalidArgumentException();
    }

    /**
     * @param ConditionQuery[] $groups
     * @param string $groupsName
     * @return $this
     */
    public function filterBy(array $groups, $groupsName)
    {
        foreach ($groups as $group) {
            if (!$group instanceof ConditionQuery) {
                throw new \InvalidArgumentException();
            }
            if (!in_array($this->columns, $group->getColumn())) {
                throw new \InvalidArgumentException();
            }
        }
        $this->query->filterGroups[$groupsName] = $groups;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
