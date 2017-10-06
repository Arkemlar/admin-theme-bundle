<?php

namespace Arkemlar\Admin\ThemeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ArkemlarAdminThemeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $container->setParameter('arkemlar_admin_theme.config', $config);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        if($config['knp_menu']['enabled'] === true){
            $loader->load('knp_menu.yml');
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $config = $this->processConfiguration(new Configuration(), $container->getExtensionConfig($this->getAlias()));
        
        if(true === isset($bundles['KnpMenuBundle'])) {
            if (true === $config['knp_menu']['enabled']) {
                $container->prependExtensionConfig(
                    'knp_menu',
                    ['twig' => ['template'  => $config['knp_menu']['template']]]
                );
            }
        }
    
        if (true === $config['twig']['enabled']) {
            $container->prependExtensionConfig(
                'twig',
                ['form_themes' => [$config['twig']['template']]]
            );
        }
    }
}
