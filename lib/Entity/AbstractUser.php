<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

class AbstractUser implements UserInterface
{
    protected $id;

    protected $email;

    protected $status;

    protected $password;

    protected $createdAt;

    protected $statuses;

    function __construct()
    {
        $this->statuses = new ArrayCollection();
    }

    public function setId(string $id): UserInterface
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setEmail(string $email): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): UserInterface
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): UserInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatuses(): ?Collection
    {
        return $this->statuses;
    }

    public function addStatus(AbstractUserStatus $status): UserInterface
    {
        if (!$this->statuses->contains($status)) {
            $status->setUser($this);
            $this->statuses->add($status);
        }
        return $this;
    }

    public function removeStatus(AbstractUserStatus $status): UserInterface
    {
        if ($this->statuses->contains($status)) {
            $this->statuses->removeElement($status);
        }
        return $this;
    }
}