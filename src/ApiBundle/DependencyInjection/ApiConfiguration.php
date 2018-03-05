<?php

namespace ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class ApiConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('api');

        $rootNode
            ->children()
                ->scalarNode('registration_confirm_template')
                    ->defaultValue('email/user_registration_confirm.html.twig')
                ->end()
                ->scalarNode('password_reset_template')
                    ->defaultValue('email/password_reset.html.twig')
                ->end()
                ->arrayNode('client')
                    ->children()
                        ->scalarNode('brand')
                            ->defaultValue('Kodear')
                        ->end()
                        ->scalarNode('base_url')
                            ->defaultValue('http://kodear.net')
                        ->end()
                        ->scalarNode('registration_confirm_url')
                            ->defaultValue('/register/confirm/')
                        ->end()
                        ->scalarNode('password_reset_url')
                            ->defaultValue('/password/reset/')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
