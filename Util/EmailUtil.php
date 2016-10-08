<?php

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
    public static function checkMailStructure($mail)
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
        if (static::checkMailStructure($mail)) {
            $domain = explode('@', $mail);

            return dns_check_record($domain, 'MX');
        }

        return false;
    }
}
