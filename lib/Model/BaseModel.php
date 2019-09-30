<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pl )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Model;

use DawBed\PHPContext\ContextInterface;
use DawBed\UserBundle\Entity\AbstractUserStatus;

abstract class BaseModel
{
    protected $entity;

    function __construct(
        UserInterface $entity,
        AbstractUserStatus $userStatus,
        ContextInterface $status
    )
    {
        $this->entity = $entity;
        $userStatus
            ->setUser($entity)
            ->setStatus($status);
        $this->entity->addStatus($userStatus);
    }

    public function getEntity(): UserInterface
    {
        return $this->entity;
    }

}