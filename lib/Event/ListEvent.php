<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Event;

use DawBed\ComponentBundle\Event\AbstractResponseEvent;
use DawBed\UserBundle\Model\ListModel;

class ListEvent extends AbstractResponseEvent
{
    private $listModel;

    public function __construct(ListModel $listModel)
    {
        $this->listModel = $listModel;
    }

    public function getListModel(): ListModel
    {
        return $this->listModel;
    }

    public function getName(): string
    {
        return __CLASS__;
    }
}