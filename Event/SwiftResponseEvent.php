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

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class SwiftResponseEvent extends Event
{
    /** @var \Swift_Events_ResponseEvent */
    protected $swiftEvent;

    /**
     * SendPerformedEvent constructor.
     * @param \Swift_Events_ResponseEvent $swiftEvent
     */
    public function __construct(\Swift_Events_ResponseEvent $swiftEvent)
    {
        $this->swiftEvent = $swiftEvent;
    }

    /**
     * @return \Swift_Events_ResponseEvent
     */
    public function getSwiftEvent()
    {
        return $this->swiftEvent;
    }
}
