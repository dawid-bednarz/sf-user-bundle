<?php
namespace DawBed\UserBundle\DTO;

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */
class PasswordSetting
{
    private $minLength;
    private $algorithm;

    function __construct(int $minLength, ?string $algorithm)
    {
        $this->minLength = $minLength;
        $this->algorithm = $algorithm;
    }

    public function getMinLength(): int
    {
        return $this->minLength;
    }

    public function getAlgorithm(): int
    {
        return (int) $this->algorithm;
    }

}