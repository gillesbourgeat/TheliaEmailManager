<?php

namespace TheliaEmailManager\Event;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class Events
{
    const SWIFT_SEND_PERFORMED = 'thelia.email.manager.swift.send';
    const SWIFT_BEFORE_SEND_PERFORMED = 'thelia.email.manager.swift.send.before';
}
