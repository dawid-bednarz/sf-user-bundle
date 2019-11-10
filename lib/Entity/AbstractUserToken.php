<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Entity;

use DawBed\ConfirmationBundle\Entity\TokenInterface;
use DawBed\ContextBundle\Entity\ContextInterface;

class AbstractUserToken
{
    protected $id;
    protected $token;
    protected $user;
    protected $context;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): AbstractUserToken
    {
        $this->id = $id;

        return $this;
    }

    public function getToken(): ?TokenInterface
    {
        return $this->token;
    }

    public function setToken(TokenInterface $token): AbstractUserToken
    {
        $this->token = $token;
        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): AbstractUserToken
    {
        $this->user = $user;
        return $this;
    }

    public function getContext(): ?ContextInterface
    {
        return $this->context;
    }

    public function setContext(ContextInterface $context): AbstractUserToken
    {
        $this->context = $context;

        return $this;
    }
}