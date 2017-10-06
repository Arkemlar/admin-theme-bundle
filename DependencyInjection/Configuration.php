<?php

namespace Arkemlar\Admin\ThemeBundle\DependencyInjection;

use Avanzu\AdminThemeBundle\Controller\BreadcrumbController;
use Avanzu\AdminThemeBundle\Controller\NavbarController;
use Avanzu\AdminThemeBundle\Controller\SidebarController;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('arkemlar_admin_theme');
    
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        
        $rootNode->children()
                    ->arrayNode('theme')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('default_avatar')->cannotBeEmpty()->end()
                            ->scalarNode('skin')
                                ->defaultValue('skin-blue')
                                ->validate()
                                    ->ifNotInArray($skins = ['skin-blue', 'skin-blue-light', 'skin-yellow', 'skin-yellow-light', 'skin-green',
                                        'skin-green-light', 'skin-purple', 'skin-purple-light', 'skin-red', 'skin-red-light', 'skin-black',
                                        'skin-black-light'])
                                    ->thenInvalid('Invalid skin type %s, allowed values are: '.implode(', ',$skins))
                                ->end()
                            ->end()
                            ->booleanNode('fixed_layout')->defaultValue(false)->end()
                            ->booleanNode('boxed_layout')->defaultValue(false)->end()
                            ->booleanNode('collapsed_sidebar')->defaultValue(false)->end()
                            ->booleanNode('mini_sidebar')->defaultValue(false)->end()
                            ->booleanNode('control_sidebar')->defaultValue(true)->end()
                            ->arrayNode('widget')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('type')->defaultValue('primary')->end()
                                    ->booleanNode('bordered')->defaultValue(true)->end()
                                    ->booleanNode('collapsible')->defaultValue(true)->end()
                                    ->scalarNode('collapsible_title')->defaultValue('Collapse')->end()
                                    ->booleanNode('removable')->defaultValue(true)->end()
                                    ->scalarNode('removable_title')->defaultValue('Remove')->end()
                                    ->booleanNode('solid')->defaultValue(false)->end()
                                    ->booleanNode('use_header')->defaultValue(true)->end()
                                    ->booleanNode('use_footer')->defaultValue(true)->end()
                                ->end()
                            ->end()
                            ->arrayNode('button')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('type')->defaultValue('primary')->end()
                                    ->scalarNode('size')->defaultValue('md')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('twig')
                        ->canBeEnabled()
                        ->children()
                            ->booleanNode('enabled')->defaultValue(true)->end()
                            ->scalarNode('template')->defaultValue('ArkemlarAdminThemeBundle::admin_form_theme.html.twig')->end()
                        ->end()
                    ->end()
                    ->arrayNode('knp_menu')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('enabled')->defaultValue(true)->end()
                            ->scalarNode('template')->defaultValue('ArkemlarAdminThemeBundle::admin_menu_theme.html.twig')->end()
                            ->scalarNode('main_menu')->defaultValue('admin_menu_main')->end()
                        ->end()
                    ->end()
                    ->arrayNode('routes')
                        ->children()
                            ->scalarNode('home')->cannotBeEmpty()->end()
                            ->scalarNode('profile')->cannotBeEmpty()->end()
                            ->scalarNode('password_reset')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
