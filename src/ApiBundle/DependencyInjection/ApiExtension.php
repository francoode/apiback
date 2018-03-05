<?php

namespace ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new ApiConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('api.registration_confirm_template', $config['registration_confirm_template']);
        $container->setParameter('api.password_reset_template', $config['password_reset_template']);
        $container->setParameter('api.client.brand', $config['client']['brand']);
        $container->setParameter('api.client.base_url', $config['client']['base_url']);
        $container->setParameter('api.client.registration_confirm_url', $config['client']['registration_confirm_url']);
        $container->setParameter('api.client.password_reset_url', $config['client']['password_reset_url']);
    }
}
