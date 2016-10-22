<?php

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
}
