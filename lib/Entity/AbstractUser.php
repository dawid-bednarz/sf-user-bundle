<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Entity;

use DateTime;
use DawBed\PHPClassProvider\ClassProvider;
use DawBed\StatusBundle\Entity\AbstractStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;

abstract class AbstractUser implements UserInterface
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

    public function addStatus(AbstractStatus $status): UserInterface
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('status', $status));
        if (!$this->statuses->matching($criteria)->count()) {
            $userStatus = ClassProvider::new(AbstractUserStatus::class);
            $userStatus->setUser($this);
            $userStatus->setStatus($status);
            $userStatus->setCreatedAt(new DateTime());
            $this->statuses->add($userStatus);
        }
        return $this;
    }

    public function removeStatus(AbstractStatus $status): UserInterface
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('status', $status));
        $matchingStatus = $this->statuses->matching($criteria);
        if ($matchingStatus->count()) {
            $this->statuses->removeElement($matchingStatus->first());
        }
        return $this;
    }

    public function hasStatus(AbstractStatus $status): bool
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('status', $status));
        $matchingStatus = $this->statuses->matching($criteria);
        return $matchingStatus->count() > 0;
    }

}