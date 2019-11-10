<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Repository;

use DawBed\ConfirmationBundle\Entity\TokenInterface;
use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUserToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass = null)
    {
        parent::__construct($registry, ClassProvider::get(AbstractUserToken::class));
    }

    public function getUserToken(TokenInterface $token): ?AbstractUserToken
    {
        $qb = $this->createQueryBuilder('ut');
        $qb->where('ut.token=:token')
            ->setParameter('token', $token);

        return $qb->getQuery()->getOneOrNullResult();
    }
}