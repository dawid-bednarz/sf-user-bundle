<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Service;

use DawBed\ConfirmationBundle\Entity\TokenInterface;
use DawBed\ConfirmationBundle\Model\WriteModel;
use DawBed\ConfirmationBundle\Service\WriteService;
use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Entity\AbstractUserToken;
use DawBed\UserBundle\Model\Criteria\TokenCriteria;
use DawBed\UserBundle\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TokenService
{
    private $tokenWriteService;
    private $entityManager;
    private $repository;

    public function __construct(
        WriteService $tokenWriteService,
        EntityManagerInterface $entityManager,
        UserTokenRepository $repository
    )
    {
        $this->tokenWriteService = $tokenWriteService;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function prepare(TokenCriteria $criteria): AbstractUserToken
    {
        /**
         * @var AbstractUserToken $entity
         */
        $entity = ClassProvider::new(AbstractUserToken::class);
        $entity->setContext($criteria->getContext());
        $entity->setToken($this->tokenWriteService->generate($criteria->getSetting()));
        $entity->setUser($criteria->getUser());

        $this->entityManager->persist($entity);

        return $entity;
    }

    public function tryGetUser(TokenInterface $token): AbstractUser
    {
        $userToken = $this->repository->getUserToken($token);

        if ($userToken === null) {
            throw new NotFoundHttpException();
        }

        return $userToken->getUser();
    }

    public function consume(TokenInterface $token): void
    {
        $this->tokenWriteService->make(WriteModel::consumedInstance($token));
    }
}