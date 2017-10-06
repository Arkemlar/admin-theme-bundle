<?php

namespace Arkemlar\Admin\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TwigPass
 */
class TwigPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $twigDefinition = $container->getDefinition('twig');
    
        $twigDefinition->addMethodCall('addGlobal', array(
                'admin_theme_config',
                new Reference('arkemlar_admin_theme.context_helper')
            )
        );
    }
}