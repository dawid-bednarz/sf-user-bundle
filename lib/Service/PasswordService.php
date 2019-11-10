<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Service;

use DawBed\UserBundle\DTO\PasswordSetting;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordService
{
    private $passwordSetting;

    function __construct(PasswordSetting $passwordSetting)
    {
        $this->passwordSetting = $passwordSetting;
    }

    public function getConstraints(): array
    {
        $constraints = [];
        $constraints[] = new Length(['min' => $this->passwordSetting->getMinLength()]);
        if (!$this->passwordSetting->isAutoGenerate()) {
            $constraints[] = new NotBlank();
        }
        return $constraints;
    }

    public function generate(): string
    {
        return $this->hash(uniqid());
    }

    public function hash(string $password): string
    {
        return password_hash($password, $this->passwordSetting->getAlgorithm());
    }

    public static function valid(string $originPassword, string $password): bool
    {
        if (password_verify($password, $originPassword) !== false) {
            return true;
        }
        return false;
    }

    public function __call($name, $arguments)
    {
        return $this->passwordSetting->$name(...$arguments);
    }
}