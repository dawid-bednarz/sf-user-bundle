<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Model\Criteria;

use DawBed\PHPStatus\StatusInterface;

class CreateCriteria
{
    private $status;

    function __construct(StatusInterface $status)
    {
        $this->status = $status;
    }

    public function getStatus(): StatusInterface
    {
        return $this->status;
    }
}