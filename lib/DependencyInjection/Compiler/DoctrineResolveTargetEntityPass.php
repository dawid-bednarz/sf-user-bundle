<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\DependencyInjection\Compiler;

use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUser;
use DawBed\UserBundle\Entity\AbstractUserStatus;
use Doctrine\ORM\Version;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineResolveTargetEntityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        $definition->addMethodCall('addResolveTargetEntity', [
            AbstractUser::class,
            ClassProvider::get(AbstractUser::class),
            [],
        ]);
        $definition->addMethodCall('addResolveTargetEntity', [
            AbstractUserStatus::class,
            ClassProvider::get(AbstractUserStatus::class),
            [],
        ]);
        $definition->addTag('doctrine.event_subscriber', ['connection' => 'default']);
    }
}