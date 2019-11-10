<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Service;

use DawBed\ComponentBundle\Service\EventDispatcher;
use DawBed\ContextBundle\Provider;
use DawBed\UserBundle\Enum\ContextEnum;
use DawBed\UserBundle\Enum\TokenEnum;
use DawBed\UserBundle\Event\ChangePasswordEvent;
use DawBed\UserBundle\Model\ChangePasswordModel;
use DawBed\UserBundle\Model\Criteria\TokenCriteria;
use \DateInterval;
use Doctrine\ORM\EntityManagerInterface;

class ChangePasswordService
{
    private $tokenService;
    private $contextProvider;
    private $eventDispatcher;
    private $passwordService;
    private $entityManager;

    public function __construct(
        TokenService $tokenService,
        Provider $contextProvider,
        EventDispatcher $eventDispatcher,
        PasswordService $passwordService,
        EntityManagerInterface $entityManager
    )
    {
        $this->tokenService = $tokenService;
        $this->contextProvider = $contextProvider;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordService = $passwordService;
        $this->entityManager = $entityManager;
    }

    public function request(AbstractUser $user): void
    {
        $context = $this->contextProvider->get(ContextEnum::CHANGE_PASSWORD);
        $tokenCriteria = new TokenCriteria(TokenEnum::CHANGE_PASSWORD_TYPE, new DateInterval('P7D'), $user, $context);
        $userToken = $this->tokenService->prepare($tokenCriteria);

        $this->eventDispatcher->dispatch(new ChangePasswordEvent($user, $userToken));
    }

    public function getPasswordService(): PasswordService
    {
        return $this->passwordService;
    }

    public function make(ChangePasswordModel $model): EntityManagerInterface
    {
        $token = $model->getToken();

        $user = $this->tokenService->tryGetUser($token);

        $user->setPassword($this->passwordService->hash($model->getPassword()));

        $model->setUser($user);

        $this->tokenService->consume($token);

        return $this->entityManager;
    }
}