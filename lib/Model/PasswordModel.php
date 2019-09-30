<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Model;

use DawBed\UserBundle\Entity\UserInterface;

class PasswordModel
{
    private $algorithm;

    function __construct(int $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function create(string $password): string
    {
        return password_hash($password, $this->algorithm);
    }

    public static function valid(UserInterface $user, string $password): bool
    {
        if (password_verify($password, $user->getPassword()) !== false) {
            return true;
        }
        return false;
    }
}