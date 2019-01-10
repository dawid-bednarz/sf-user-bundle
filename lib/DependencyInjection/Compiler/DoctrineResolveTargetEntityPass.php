<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle\DependencyInjection\Compiler;

use DawBed\PHPUser\UserInterface;
use DawBed\PHPUser\UserStatusInterface;
use DawBed\UserBundle\Service\EntityService;
use Doctrine\ORM\Version;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineResolveTargetEntityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $entityService = $container->get(EntityService::class);

        $definition = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        $definition->addMethodCall('addResolveTargetEntity', [
            UserInterface::class,
            $entityService->User,
            [],
        ]);
        $definition->addMethodCall('addResolveTargetEntity', [
            UserStatusInterface::class,
            $entityService->UserStatus,
            [],
        ]);

        if (version_compare(Version::VERSION, '2.5.0-DEV') < 0) {
            $definition->addTag('doctrine.event_listener', ['event' => 'loadClassMetadata']);
        } else {
            $definition->addTag('doctrine.event_subscriber', ['connection' => 'default']);
        }
    }
}