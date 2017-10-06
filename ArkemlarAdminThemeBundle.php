<?php

namespace Arkemlar\Admin\ThemeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Arkemlar\Admin\ThemeBundle\DependencyInjection\Compiler\TwigPass;

class ArkemlarAdminThemeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigPass());
    }
}
