<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Model\Criteria;

use DawBed\ContextBundle\Entity\Context;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\ConfirmationBundle\Model\Criteria\TokenCriteria as BaseCriteria;
use DateInterval;

class TokenCriteria
{
    private $setting;
    private $user;
    private $context;

    public function __construct(
        string $type,
        DateInterval $dateInterval,
        AbstractUser $user,
        Context $context
    )
    {
        $this->user = $user;
        $this->context = $context;
        $this->setting = new BaseCriteria($dateInterval, $type);
    }

    public function getSetting(): BaseCriteria
    {
        return $this->setting;
    }

    public function setSetting(TokenCriteria $setting): TokenCriteria
    {
        $this->setting = $setting;
        return $this;
    }

    public function getUser(): AbstractUser
    {
        return $this->user;
    }

    public function setUser(AbstractUser $user): TokenCriteria
    {
        $this->user = $user;
        return $this;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function setContext(Context $context): TokenCriteria
    {
        $this->context = $context;
        return $this;
    }
}