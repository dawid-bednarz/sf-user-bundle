<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Event;

use DawBed\ComponentBundle\Event\AbstractResponseEvent;
use DawBed\UserBundle\Entity\AbstractUser;

class AbstractEvent extends AbstractResponseEvent
{
    private $user;

    public function __construct(AbstractUser $user)
    {
        $this->user = $user;
    }

    public function getUser(): AbstractUser
    {
        return $this->user;
    }

    public function getName(): string
    {
        return static::class;
    }
}