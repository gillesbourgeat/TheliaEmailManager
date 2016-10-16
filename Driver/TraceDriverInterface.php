<?php

namespace TheliaEmailManager\Driver;

use SublimeMessageHistory\Criteria\EmailCriteria;
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
     * @param EmailCriteria $emailCriteria
     * @return EmailEntityCollection
     */
    public function find(EmailCriteria $emailCriteria);

    /**
     * @param EmailCriteria $emailCriteria
     * @return int
     */
    public function delete(EmailCriteria $emailCriteria);
}
