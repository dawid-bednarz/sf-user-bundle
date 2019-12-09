<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Model;

use DawBed\ComponentBundle\Enum\WriteTypeEnum;
use DawBed\ComponentBundle\Validator\Constraints\UniqueEntityInterface;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Model\Criteria\WriteCriteria;

class WriteModel implements UniqueEntityInterface
{
    private $entity;
    private $email;
    private $password;
    private $criteria;

    public function __construct(WriteCriteria $criteria, AbstractUser $entity)
    {
        $this->criteria = $criteria;
        $this->entity = $entity;
        if ($criteria->hasStatus()) {
            $this->entity->addStatus($criteria->getStatus());
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
        if ($this->is(WriteTypeEnum::CREATE)) {
            $this->entity->setEmail($this->email);
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

    public function isChangePassword(): bool
    {
        return $this->criteria->isChangePassword();
    }

    public function setChangePassword(bool $changePassword): WriteModel
    {
        $this->criteria->setChangePassword($changePassword);

        return $this;
    }

    public function isChangeEmail(): bool
    {
        return $this->criteria->isChangeEmail();
    }

    public function setChangeEmail(bool $changeEmail): WriteModel
    {
        $this->criteria->setChangeEmail($changeEmail);

        return $this;
    }
}