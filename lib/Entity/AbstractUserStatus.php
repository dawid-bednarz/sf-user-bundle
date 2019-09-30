<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Entity;

use DawBed\PHPContext\ContextInterface;

class AbstractUserStatus 
{
    protected $id;
    protected $status;
    protected $user;

    public function setUser(UserInterface $user): AbstractUserStatus
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getStatus(): ContextInterface
    {
        return $this->status;
    }

    public function setStatus(ContextInterface $status): AbstractUserStatus
    {
        $this->status = $status;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): AbstractUserStatus
    {
        $this->id = $id;
        return $this;
    }
}