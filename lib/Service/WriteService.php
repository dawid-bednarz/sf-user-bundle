<?php

namespace DawBed\UserBundle\Service;

use DawBed\ComponentBundle\Enum\WriteTypeEnum;
use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Model\WriteModel;
use DawBed\UserBundle\Model\Criteria\WriteCriteria;
use DawBed\UserBundle\Utils\ChangeEmail;
use DawBed\UserBundle\Utils\ChangePassword;
use DawBed\UserBundle\Utils\Password;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */
class WriteService
{
    private $entityManager;
    private $container;
    private $password;

    function __construct(EntityManagerInterface $entityManager,
                         Password $password,
                         ContainerInterface $container
    )
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->password = $password;
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

        return new WriteModel($criteria, $entity);
    }

    public function make(WriteModel $model): EntityManagerInterface
    {
        if ($model->is(WriteTypeEnum::DELETE)) {
            $this->entityManager->remove($model->getEntity());
            return $this->entityManager;
        }
        if ($model->is(WriteTypeEnum::CREATE)) {
            $this->create($model);
        } elseif ($model->is(WriteTypeEnum::UPDATE)) {
            $this->update($model);
        }
        if (!$model->getCriteria()->isByDifferentUser()) {
            $model->getEntity()->setPassword($this->password->hash($model->getPassword()));
        }

        return $this->entityManager;
    }

    private function create(WriteModel $model): void
    {
        $entity = $model->prepareEntity();
        $criteria = $model->getCriteria();

        if ($criteria->isByDifferentUser()) {
            $this->container->get(ChangePassword::class)->request($entity);
            $entity->setPassword($this->password->generate());
        }
        $this->entityManager->persist($entity);
    }

    private function update(WriteModel $model): void
    {
        $entity = $model->prepareEntity();
        $criteria = $model->getCriteria();

        if ($criteria->isChangePassword()) {
            $this->container->get(ChangePassword::class)->request($entity);
        }
        if ($criteria->isChangeEmail() || $model->getEmail() !== $entity->getEmail()) {
            $this->container->get(ChangeEmail::class)->request($entity, $model->getEmail());
        }

        $this->entityManager->persist($entity);
    }
}