<?php

namespace ApiSecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ApiSecurityExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new ApiSecurityConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->defineApiKeyExtractor($config, $container);
    }

    private function defineApiKeyExtractor(array $config, ContainerBuilder $container)
    {
        $container->setParameter('api_security.parameter_name', $config['parameter_name']);
        $container->setParameter('api_security.expiration_days', $config['expiration_days']);
        $container->setAlias('api_security.key_extractor', 'api_security.key_extractor.' . $config['delivery']);
    }
}
