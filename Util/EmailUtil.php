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

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailUtil
{
    /**
     * for check if the email is valid
     * @param string $mail
     * @return bool
     */
    public static function checkEmailStructure($mail)
    {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $mail Check DNS records corresponding to a given Internet host name
     * @return bool true if any records are found; returns false if no records
     * were found or if an error occurred.
     */
    public static function checkMailDNS($mail)
    {
        if (static::checkEmailStructure($mail)) {
            $domain = explode('@', $mail);

            return dns_check_record($domain, 'MX');
        }

        return false;
    }
}
