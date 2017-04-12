<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
