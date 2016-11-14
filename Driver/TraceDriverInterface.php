<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Driver;

use TheliaEmailManager\Query\EmailHistoryQuery;
use TheliaEmailManager\Entity\EmailEntity;
use TheliaEmailManager\Entity\EmailEntityCollection;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
interface TraceDriverInterface
{
    /**
     * @param EmailEntity $emailEntity
     * @return bool
     */
    public function push(EmailEntity $emailEntity);

    /**
     * @param EmailHistoryQuery $query
     * @return EmailEntityCollection
     */
    public function find(EmailHistoryQuery $query);

    /**
     * @param EmailHistoryQuery $query
     * @return int
     */
    public function delete(EmailHistoryQuery $query);
}
