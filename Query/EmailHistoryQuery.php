<?php

namespace TheliaEmailManager\Query;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailHistoryQuery extends AbstractQuery
{
    const COL_ID = 'id';
    const COL_FROM = 'from';
    const COL_TO = 'to';
    const COL_CC = 'cc';
    const COL_BCC = 'bcc';
    const COL_REPLAY_TO = 'replay_to';
    const COL_SUBJECT = 'subject';
    const COL_BODY = 'body';
    const COL_DATE = 'date';

    protected $columns = [
        self::COL_ID,
        self::COL_FROM,
        self::COL_TO,
        self::COL_CC,
        self::COL_BCC,
        self::COL_REPLAY_TO,
        self::COL_SUBJECT,
        self::COL_BODY,
        self::COL_DATE
    ];
}
