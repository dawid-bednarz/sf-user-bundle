<?php

namespace DawBed\UserBundle\Service;

use DawBed\ComponentBundle\Enum\WriteTypeEnum;
use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Model\WriteModel;
use DawBed\UserBundle\Model\Criteria\WriteCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */
class WriteService
{
    private $entityManager;
    private $passwordService;

    function __construct(EntityManagerInterface $entityManager,
                         PasswordService $passwordService
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordService = $passwordService;
    }

    public function prepareModel(WriteCriteria $criteria): WriteModel
    {
        if ($criteria->is(WriteTypeEnum::UPDATE) || $criteria->is(WriteTypeEnum::DELETE)) {
            $entity = $this->entityManager->getRepository(AbstractUser::class)->findOneBy([
                'id' => $criteria->getId()
            ]);
        } else {
            $entity = ClassProvider::new(AbstractUser::class);
        }
        if ($entity === null) {
            throw new NotFoundHttpException();
        }
        if ($criteria->hasStatus()) {
            $entity->addStatus($criteria->getStatus());
        }
        $criteria->setPasswordAlgorithm($this->passwordService->getAlgorithm());

        return new WriteModel($criteria, $entity);
    }

    public function make(WriteModel $model): EntityManagerInterface
    {
        if (!$model->is(WriteTypeEnum::DELETE)) {
            $this->write($model);
        } else {
            $this->entityManager->remove($model->getEntity());
        }

        return $this->entityManager;
    }

    private function write(WriteModel $model): void
    {
        if ($this->passwordService->isAutoGenerate() && $model->hasPassword()) {
            $model->setPassword($this->passwordService->generate());
        }
        $this->entityManager->persist($model->prepareEntity());
    }
}