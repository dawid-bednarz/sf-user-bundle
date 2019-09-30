<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Model;

use DawBed\PHPContext\ContextInterface;
use DawBed\UserBundle\Entity\AbstractUserStatus;
use DawBed\UserBundle\Entity\UserInterface;

class CreateModel extends BaseModel
{
    protected $password;
    protected $passwordModel;

    function __construct(UserInterface $entity,
                         AbstractUserStatus $userStatus,
                         ContextInterface $status,
                         int $passwordAlgorithm
    )
    {
        parent::__construct($entity, $userStatus, $status);
        $this->passwordModel = new PasswordModel($passwordAlgorithm);
    }

    public function make(): UserInterface
    {
        $this->entity->setPassword($this->hashPassword())
            ->setCreatedAt(new \DateTime('NOW'));

        return $this->entity;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function hashPassword()
    {
        return $this->passwordModel->create($this->password);
    }
}