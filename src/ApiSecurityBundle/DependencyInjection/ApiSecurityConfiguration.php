<?php

namespace ApiSecurityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class ApiSecurityConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api_security');

        $rootNode
            ->children()
                ->scalarNode('delivery')
                    ->defaultValue('header')
                    ->validate()
                        ->ifNotInArray(array('header', 'query'))
                        ->thenInvalid('Unknown authentication key delivery type "%s".')
                    ->end()
                ->end()
                ->scalarNode('parameter_name')
                    ->defaultValue('X-UADVENTURE-API-KEY')
                ->end()
                ->scalarNode('expiration_days')
                    ->defaultValue('30')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
