<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pl )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;

interface UserInterface
{
    public function setId(string $id): UserInterface;

    public function getId():?string;

    public function getEmail(): ?string;

    public function getPassword(): ?string;

    public function setEmail(string $email): UserInterface;

    public function setPassword(string $password): UserInterface;

    public function getCreatedAt(): ?DateTime;

    public function setCreatedAt(DateTime $createdAt): UserInterface;

    public function getStatuses() : ?Collection;

    public function addStatus(AbstractUserStatus $status): UserInterface;

    public function removeStatus(AbstractUserStatus $status): UserInterface;

}