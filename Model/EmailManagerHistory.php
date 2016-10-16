<?php

namespace TheliaEmailManager\Model;

use TheliaEmailManager\Model\Base\EmailManagerHistory as BaseEmailManagerHistory;

class EmailManagerHistory extends BaseEmailManagerHistory
{
    /**
     * @param string $v
     * @return $this
     */
    public function setBody($v)
    {
        return parent::setBody($v);
    }
}
