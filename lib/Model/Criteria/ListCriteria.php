<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Model\Criteria;

class ListCriteria extends \DawBed\ComponentBundle\Criteria\ListCriteria
{
    public function __construct()
    {
        $this->setAvailableOrderBy(['createdAt','email']);
        $this->addOrderBy('createdAt', 'DESC');
    }
}