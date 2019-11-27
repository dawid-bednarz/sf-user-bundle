<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Model;

use DawBed\ConfirmationBundle\Entity\TokenInterface;
use DawBed\UserBundle\Entity\UserInterface;

class ChangeEmailModel
{
    private $email;
    private $token;
    private $user;

    public function getUser() : ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user) : ChangeEmailModel
    {
        $this->user = $user;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): ChangeEmailModel
    {
        $this->email = $email;
        return $this;
    }

    public function getToken(): ?TokenInterface
    {
        return $this->token;
    }

    public function setToken(TokenInterface $token): ChangeEmailModel
    {
        $this->token = $token;
        return $this;
    }
}