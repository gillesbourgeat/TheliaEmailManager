<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Event;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class Events
{
    const SWIFT_SEND_PERFORMED = 'thelia.email.manager.swift.send';
    const SWIFT_BEFORE_SEND_PERFORMED = 'thelia.email.manager.swift.send.before';

    const TRACE_CREATE = 'thelia.email.manager.trace.create';
    const TRACE_UPDATE = 'thelia.email.manager.trace.update';
    const TRACE_UNLINK = 'thelia.email.manager.trace.unlink';

    const EMAIL_CREATE = 'thelia.email.manager.email.create';
    const EMAIL_UPDATE = 'thelia.email.manager.email.update';
}
