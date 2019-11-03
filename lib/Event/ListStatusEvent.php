<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Event;

use DawBed\ComponentBundle\Event\AbstractResponseEvent;

class ListStatusEvent extends AbstractResponseEvent
{
    private $statuses;

    public function __construct(array $statuses)
    {
        $this->statuses = $statuses;
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function getName(): string
    {
        return __CLASS__;
    }
}