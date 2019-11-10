<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Model;

use DawBed\ConfirmationBundle\Entity\AbstractToken;
use DawBed\UserBundle\Entity\AbstractUser;

class ChangePasswordModel
{
    private $user;
    private $password;
    private $token;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): ChangePasswordModel
    {
        $this->password = $password;
        return $this;
    }

    public function getToken(): ?AbstractToken
    {
        return $this->token;
    }

    public function setToken(AbstractToken $token): ChangePasswordModel
    {
        $this->token = $token;

        return $this;
    }

    public function setUser(AbstractUser $user): ChangePasswordModel
    {
        $this->user = $user;

        return $this;

    }

    public function getUser(): ?AbstractUser
    {
        return $this->user;
    }
}