<?php

namespace DawBed\UserBundle\Controller;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
class UserController extends AbstractController
{
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $entityManager->getRepository(User::class)->find('5b77d85f-e2df-11e9-b1d9-0242c0a86003');

        dd($user->getStatuses());
    }

    public function update(string $id, Request $request)
    {

    }
}