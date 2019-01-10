<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Service;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordService
{
    private $minLength;
    private $autoGenerate;
    private $algorithm;

    function __construct(int $minLength, bool $autoGenerate, string $algorithm)
    {
        $this->minLength = $minLength;
        $this->autoGenerate = $autoGenerate;
        $this->algorithm = $algorithm;
    }

    public function getConstraints(): array
    {
        $constraints = [];
        $constraints[] = new Length(['min' => $this->minLength]);
        if (!$this->autoGenerate) {
            $constraints[] = new NotBlank();
        }
        return $constraints;
    }

    public function getMinLength(): int
    {
        return $this->minLength;
    }

    public function isAutoGenerate(): bool
    {
        return $this->autoGenerate;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function generate(): string
    {
        return uniqid();
    }

}