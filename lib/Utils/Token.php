<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Utils;

use DawBed\ConfirmationBundle\Entity\TokenInterface;
use DawBed\ConfirmationBundle\Model\Criteria\TokenCriteria;
use DawBed\ConfirmationBundle\Model\WriteModel;
use DawBed\ConfirmationBundle\Service\WriteService;
use DawBed\ContextBundle\Entity\Context;
use DawBed\ContextBundle\Provider;
use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Entity\AbstractUserToken;
use DawBed\UserBundle\Enum\ContextEnum;
use DawBed\UserBundle\Enum\TokenEnum;
use DawBed\UserBundle\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DateInterval;

class Token
{
    private $tokenWriteService;
    private $entityManager;
    private $repository;
    private $contextProvider;

    public function __construct(
        WriteService $tokenWriteService,
        EntityManagerInterface $entityManager,
        UserTokenRepository $repository,
        Provider $contextProvider
    )
    {
        $this->tokenWriteService = $tokenWriteService;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->contextProvider = $contextProvider;
    }

    public function prepare(string $type, AbstractUser $user, array $data = []): AbstractUserToken
    {
        /**
         * @var AbstractUserToken $entity
         */
        $entity = ClassProvider::new(AbstractUserToken::class);
        $entity->setContext($this->prepareContext($type));
        $entity->setToken($this->generateToken($type, $data));
        $entity->setUser($user);

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

    private function prepareContext(string $type): Context
    {
        switch ($type) {
            case TokenEnum::CHANGE_EMAIL_TYPE:
                return $this->contextProvider->get(ContextEnum::CHANGE_EMAIL);
                break;
            case TokenEnum::CHANGE_PASSWORD_TYPE:
                return $this->contextProvider->get(ContextEnum::CHANGE_PASSWORD);
                break;
        }
    }

    private function generateToken(string $type, array $data = []): TokenInterface
    {
        switch ($type) {
            case TokenEnum::CHANGE_EMAIL_TYPE:
                return $this->tokenWriteService->generate(new TokenCriteria(new DateInterval('P7D'), $type, $data));
                break;
            case TokenEnum::CHANGE_PASSWORD_TYPE:
                return $this->tokenWriteService->generate(new TokenCriteria(new DateInterval('P7D'), $type));
                break;
        }
    }
}