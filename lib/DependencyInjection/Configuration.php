<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserBundle\DependencyInjection;

use DawBed\ComponentBundle\Configuration\Entity;
use DawBed\PHPUser\User;
use DawBed\PHPUser\UserStatus;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(UserExtension::ALIAS);

        $this->passwordOptions($rootNode);

        $entity = new Entity($rootNode);
        $entity
            ->new('user', User::class)
            ->new('userStatus', UserStatus::class)
            ->end();

        return $treeBuilder;
    }

    public function passwordOptions(ArrayNodeDefinition $rootNode)
    {
        $password = $rootNode
            ->children()
            ->arrayNode('password')
            ->isRequired()
            ->children();
        $password
            ->scalarNode('auto_generate')
            ->end();
        $password
            ->scalarNode('min_length')
            ->validate()
            ->ifTrue(function ($v) {
                return !is_numeric($v);
            })
            ->thenInvalid('must be integer')
            ->end();
        $password
            ->scalarNode('algorithm')
            ->validate()
            ->ifTrue(function ($v) {
                return !is_string(password_hash('123', $v));
            })
            ->thenInvalid('not supported')
            ->end();
    }
}