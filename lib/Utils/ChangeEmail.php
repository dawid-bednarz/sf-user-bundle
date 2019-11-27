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
use DawBed\UserBundle\Event\ChangeEmailEvent;
use DawBed\UserBundle\Model\ChangeEmailModel;
use Doctrine\ORM\EntityManagerInterface;

class ChangeEmail
{
    private $token;
    private $eventDispatcher;
    private $entityManager;
    private $statusProvider;

    public function __construct(
        Token $token,
        Provider $statusProvider,
        EventDispatcher $eventDispatcher,
        EntityManagerInterface $entityManager
    )
    {
        $this->token = $token;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->statusProvider = $statusProvider;
    }

    public function request(AbstractUser $user, string $newEmail): void
    {
        $user->addStatus($this->statusProvider->get(StatusEnum::CHANGE_EMAIL));

        $userToken = $this->token->prepare(TokenEnum::CHANGE_EMAIL_TYPE, $user, [
            'email' => $newEmail
        ]);

        $this->eventDispatcher->dispatch(new ChangeEmailEvent($user, $userToken));
    }

    public function make(ChangeEmailModel $model): EntityManagerInterface
    {
        $token = $model->getToken();

        $user = $this->token->tryGetUser($token);
        $user->setEmail($token->getData()['email']);
        $user->removeStatus($this->statusProvider->get(StatusEnum::CHANGE_EMAIL));

        $this->token->consume($token);

        $model->setUser($user);

        return $this->entityManager;
    }
}