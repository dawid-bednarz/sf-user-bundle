<?php

/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Service;

use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Model\Criteria\ListCriteria;
use DawBed\UserBundle\Model\ListModel;
use DawBed\UserBundle\Repository\UserRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function item(string $id): AbstractUser
    {
        /**
         * @var AbstractUser $entity
         */
        $entity = $this->repository->find($id);

        if ($entity === null) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    public function list(ListCriteria $criteria): ListModel
    {
        $qb = $this->repository->createQueryBuilder('u');

        $listModel = new ListModel();

        foreach ($criteria->getOrderBy() as $field => $value) {
            $qb->addOrderBy('u.' . $field, $value);
        }
        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($qb));

        try {
            $pagerfanta->setMaxPerPage($criteria->getItemsOnPage());
            $pagerfanta->setCurrentPage($criteria->getPage());
        } catch (\Exception $exception) {
            $listModel->setPage($pagerfanta->getNbPages());
            return $listModel;
        }
        $listModel
            ->setSortColumns($criteria->getOrderBy())
            ->setAvailableSortColumns($criteria->getAvailableOrderBy())
            ->setPage($pagerfanta->getNbResults() === 0 ? 0 : $pagerfanta->getNbPages())
            ->setTotal($pagerfanta->getNbResults());

        foreach ($pagerfanta->getCurrentPageResults() as $user) {
            $listModel->append($user);
        }

        return $listModel;
    }
}