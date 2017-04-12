<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager\Event;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class Events
{
    const SWIFT_SEND_PERFORMED = 'thelia.email.manager.swift.send';
    const SWIFT_BEFORE_SEND_PERFORMED = 'thelia.email.manager.swift.send.before';
    const SWIFT_EXCEPTION_THROWN = 'thelia.email.manager.swift.exception.thrown';
    const SWIFT_RESPONSE_RECEIVED = 'thelia.email.manager.swift.response.received';

    const TRACE_CREATE = 'thelia.email.manager.trace.create';
    const TRACE_UPDATE = 'thelia.email.manager.trace.update';
    const TRACE_UNLINK = 'thelia.email.manager.trace.unlink';

    const EMAIL_CREATE = 'thelia.email.manager.email.create';
    const EMAIL_UPDATE = 'thelia.email.manager.email.update';
}
