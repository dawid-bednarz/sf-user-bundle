<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Utils;

use DawBed\ComponentBundle\Service\EventDispatcher;
use DawBed\StatusBundle\Provider;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Enum\StatusEnum;
use DawBed\UserBundle\Enum\TokenEnum;
use DawBed\UserBundle\Event\ChangePasswordEvent;
use DawBed\UserBundle\Model\ChangePasswordModel;
use Doctrine\ORM\EntityManagerInterface;

class ChangePassword
{
    private $tokenService;
    private $eventDispatcher;
    private $passwordService;
    private $entityManager;
    private $statusProvider;

    public function __construct(
        Token $token,
        Provider $statusProvider,
        EventDispatcher $eventDispatcher,
        Password $password,
        EntityManagerInterface $entityManager
    )
    {
        $this->tokenService = $token;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordService = $password;
        $this->entityManager = $entityManager;
        $this->statusProvider = $statusProvider;
    }

    public function request(AbstractUser $user): void
    {
        $user->addStatus($this->statusProvider->get(StatusEnum::CHANGE_PASSWORD));

        $userToken = $this->tokenService->prepare(TokenEnum::CHANGE_PASSWORD_TYPE, $user);

        $this->eventDispatcher->dispatch(new ChangePasswordEvent($user, $userToken));
    }

    public function make(ChangePasswordModel $model): EntityManagerInterface
    {
        $token = $model->getToken();

        $user = $this->tokenService->tryGetUser($token);

        $user->removeStatus($this->statusProvider->get(StatusEnum::CHANGE_PASSWORD))
            ->setPassword($this->passwordService->hash($model->getPassword()));

        $model->setUser($user);

        $this->tokenService->consume($token);

        return $this->entityManager;
    }
}