<?php
/**
 * Created by PhpStorm.
 * User: q3
 * Date: 17.08.2018
 * Time: 20:30
 */

namespace DawBed\UserBundle\Service;

use DawBed\PHPClassProvider\ClassProvider;
use DawBed\PHPUser\Context;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Entity\AbstractUserStatus;
use DawBed\UserBundle\Model\CreateModel;
use DawBed\UserBundle\Model\Criteria\CreateCriteria;
use Doctrine\ORM\EntityManagerInterface;
use DawBed\StatusBundle\Service\CreateService as StatusCreateService;

class CreateService
{
    private $entityManager;
    private $statusService;
    private $passwordService;

    function __construct(
        EntityManagerInterface $entityManager,
        StatusCreateService $statusService,
        PasswordService $passwordService
    )
    {
        $this->entityManager = $entityManager;
        $this->statusService = $statusService;
        $this->passwordService = $passwordService;
    }

    public function prepareModel(CreateCriteria $criteria): CreateModel
    {
        return new CreateModel(
            ClassProvider::new(AbstractUser::class),
            ClassProvider::new(AbstractUserStatus::class),
            $criteria->getStatus(),
            $this->passwordService->getAlgorithm()
        );
    }

    public function make(CreateModel $model): EntityManagerInterface
    {
        if ($this->passwordService->isAutoGenerate()) {
            $model->setPassword($this->passwordService->generate());
        }
        $entity = $model->make();

        $this->entityManager->persist($entity);

        return $this->entityManager;
    }

}