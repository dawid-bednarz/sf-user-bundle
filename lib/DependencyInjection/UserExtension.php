<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\DependencyInjection;

use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Service\PasswordService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class UserExtension extends Extension implements PrependExtensionInterface
{
    const ALIAS = 'dawbed_user_bundle';

    public function prepend(ContainerBuilder $container): void
    {
        $container->setParameter('user_bundle_dir', dirname(__DIR__));
        $loader = $this->prepareLoader($container);
        $loader->load('services.yaml');
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->prepareLoader($container);
        $this->prepareEntityProvider($config['entities'], $container);
        $this->preparePasswordService($config['password'], $container);
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    private function prepareLoader(ContainerBuilder $containerBuilder): YamlFileLoader
    {
        return new YamlFileLoader($containerBuilder, new FileLocator(dirname(__DIR__) . '/Resources/config'));
    }

    private function prepareEntityProvider(array $entities, ContainerBuilder $container) : void
    {
        foreach ($entities as $name => $class) {
            ClassProvider::add($name,$class);
        }
    }

    private function preparePasswordService(array $passwordOptions, ContainerBuilder $container) : void
    {
        $container->setDefinition(PasswordService::class, new Definition(PasswordService::class, [
                $passwordOptions['min_length'],
                $passwordOptions['auto_generate'],
                $passwordOptions['algorithm']
            ]
        ));
    }
}