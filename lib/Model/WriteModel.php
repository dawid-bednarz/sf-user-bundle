<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Model;

use DawBed\ComponentBundle\Enum\WriteTypeEnum;
use DawBed\ComponentBundle\Validator\Constraints\UniqueEntityInterface;
use DawBed\StatusBundle\Entity\AbstractStatus;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Entity\AbstractUserStatus;
use DawBed\UserBundle\Model\Criteria\WriteCriteria;
use Doctrine\Common\Collections\ArrayCollection;

class WriteModel implements UniqueEntityInterface
{
    private $entity;
    private $email;
    private $password;
    private $statuses;
    private $criteria;

    public function __construct(WriteCriteria $criteria, AbstractUser $entity)
    {
        $this->statuses = new ArrayCollection();
        $this->criteria = $criteria;
        $this->entity = $entity;
        if ($criteria->hasStatus()) {
            $this->statuses->add($criteria->getStatus());
        }
    }

    public function getEntity(): AbstractUser
    {
        return $this->entity;
    }

    public function getOldUniqueValue()
    {
        return $this->entity->getEmail();
    }

    public function prepareEntity(): AbstractUser
    {
        $this->entity->getStatuses()->map(function (AbstractUserStatus $us) {
            if (!$this->getStatuses()->contains($us->getStatus())) {
                $this->entity->removeStatus($us->getStatus());
            }
        });
        $this->getStatuses()->map(function (AbstractStatus $status) {
            $this->entity->addStatus($status);
        });
        $this->entity
            ->setEmail($this->email)
            ->setPassword($this->password);

        if ($this->is(WriteTypeEnum::CREATE)) {
            $this->entity->setCreatedAt(new \DateTime('NOW'));
        }

        return $this->entity;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): WriteModel
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): WriteModel
    {
        $this->email = $email;
        return $this;
    }

    public function getStatuses(): ArrayCollection
    {
        return $this->statuses;
    }

    public function setStatuses(ArrayCollection $statuses): WriteModel
    {
        $this->statuses = $statuses;
        return $this;
    }

    public function hasPassword(): bool
    {
        return $this->password !== null;
    }

    public function getCriteria(): WriteCriteria
    {
        return $this->criteria;
    }

    public function is(string $type): bool
    {
        return $this->criteria->is($type);
    }
}