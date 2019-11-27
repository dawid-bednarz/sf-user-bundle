<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Event;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Entity\AbstractUserToken;

class AbstractTokenEvent extends AbstractEvent
{
    private $userToken;

    public function __construct(AbstractUser $user, AbstractUserToken $userToken)
    {
        parent::__construct($user);
        $this->userToken = $userToken;
    }

    public function getUserToken(): AbstractUserToken
    {
        return $this->userToken;
    }
}