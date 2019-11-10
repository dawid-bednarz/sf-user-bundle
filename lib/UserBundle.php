<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserBundle;

use DawBed\ComponentBundle\DependencyInjection\ChildrenBundle\ComponentBundleInterface;
use DawBed\UserBundle\DependencyInjection\Compiler\DoctrineResolveTargetEntityPass;
use DawBed\UserBundle\DependencyInjection\UserExtension;
use DawBed\UserBundle\Event\Events;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle implements ComponentBundleInterface
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DoctrineResolveTargetEntityPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1000);
        $this->addRegisterMappingsPass($container);
    }

    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $mappings = array(
            realpath(__DIR__ . '/Resources/config/schema') => 'DawBed\UserBundle\Entity',
        );

        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings));
        }
    }

    public static function getAlias(): string
    {
        return UserExtension::ALIAS;
    }

    public static function getEvents(): ?string
    {
        return Events::class;
    }

    public function getContainerExtension(): UserExtension
    {
        return new UserExtension();
    }
}