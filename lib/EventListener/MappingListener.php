<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\EventListener;

use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Entity\AbstractUserStatus;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class MappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if ($classMetadata->getName() !== ClassProvider::get(AbstractUser::class)) {
            return;
        }
        $classMetadata->mapOneToMany(array(
            'targetEntity' => ClassProvider::get(AbstractUserStatus::class),
            'fieldName' => 'statuses',
            'cascade' => ['persist'],
            'orphanRemoval' => true,
            'mappedBy' => 'user'
        ));
    }
}