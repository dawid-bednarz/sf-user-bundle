<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\Event;

use DawBed\ComponentBundle\Event\AbstractEvents;

class Events extends AbstractEvents
{
    const CREATE_RESPONSE = CreateEvent::class;
    const UPDATE_RESPONSE = UpdateEvent::class;
    const GET_ITEM_RESPONSE = GetItemEvent::class;
    const LIST_RESPONSE = ListEvent::class;
    const LIST_STATUS_RESPONSE = ListStatusEvent::class;
    const DELETE_ITEM_RESPONSE = DeleteEvent::class;
    const CHANGE_PASSWORD = ChangePasswordEvent::class;
    const CHANGED_PASSWORD = ChangedPasswordEvent::class;

    const ALL = [
        self::CREATE_RESPONSE => self::REQUIRED,
        self::UPDATE_RESPONSE => self::REQUIRED,
        self::GET_ITEM_RESPONSE => self::REQUIRED,
        self::LIST_RESPONSE => self::REQUIRED,
        self::LIST_STATUS_RESPONSE => self::REQUIRED,
        self::DELETE_ITEM_RESPONSE => self::REQUIRED,
        self::CHANGE_PASSWORD => self::REQUIRED,
        self::CHANGED_PASSWORD => self::REQUIRED
    ];

    protected function getAll(): array
    {
        return self::ALL;
    }
}