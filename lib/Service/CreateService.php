<?php
/**
 * Created by PhpStorm.
 * User: q3
 * Date: 17.08.2018
 * Time: 20:30
 */

namespace DawBed\UserBundle\Service;

use DawBed\PHPUser\Model\User\Criteria\CreateCriteria;
use DawBed\PHPUser\Model\User\CreateModel;
use DawBed\PHPUser\Context;
use Doctrine\ORM\EntityManagerInterface;
use DawBed\StatusBundle\Service\CreateService as StatusCreateService;

class CreateService
{
    private $entityManager;
    private $entityService;
    private $statusService;
    private $passwordService;

    function __construct(
        EntityManagerInterface $entityManager,
        EntityService $entityService,
        StatusCreateService $statusService,
        PasswordService $passwordService
    )
    {
        $this->entityManager = $entityManager;
        $this->entityService = $entityService;
        $this->statusService = $statusService;
        $this->passwordService = $passwordService;
    }

    public function prepareModel(CreateCriteria $criteria): CreateModel
    {
        $user = new $this->entityService->User;
        $userStatus = new $this->entityService->UserStatus;

        return new CreateModel(
            $user,
            $userStatus,
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