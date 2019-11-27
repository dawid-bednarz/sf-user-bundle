<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Model\Criteria;

use DawBed\ComponentBundle\Enum\WriteTypeEnum;
use DawBed\StatusBundle\Entity\AbstractStatus;

class WriteCriteria
{
    private $id;
    private $status;
    private $byDifferentUser = false;
    private $type;

    public function __construct(string $type)
    {
        $this->type = new WriteTypeEnum($type);
    }

    public function setId(string $id): WriteCriteria
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setStatus(AbstractStatus $status): WriteCriteria
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?AbstractStatus
    {
        return $this->status;
    }

    public function hasStatus(): bool
    {
        return $this->status !== null;
    }

    public function isByDifferentUser(): bool
    {
        return $this->byDifferentUser;
    }

    public function setByDifferentUser(bool $byDifferentUser): WriteCriteria
    {
        $this->byDifferentUser = $byDifferentUser;

        return $this;
    }

    public function is(string $type): bool
    {
        return $this->type->is($type);
    }
}