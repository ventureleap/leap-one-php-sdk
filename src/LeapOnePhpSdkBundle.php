<?php

namespace VentureLeap\LeapOnePhpSdk;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use VentureLeap\LeapOnePhpSdk\DependencyInjection\Compiler\AddProviderCompilerPass;
use VentureLeap\LeapOnePhpSdk\DependencyInjection\Compiler\CustomConfigurationCompilerPass;
use VentureLeap\LeapOnePhpSdk\DependencyInjection\Compiler\StorageConfigurationCompilerPass;
use VentureLeap\LeapOnePhpSdk\DependencyInjection\Compiler\UserProviderCompilerPass;

class LeapOnePhpSdkBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddProviderCompilerPass());
        $container->addCompilerPass(new StorageConfigurationCompilerPass());
        $container->addCompilerPass(new CustomConfigurationCompilerPass());
        $container->addCompilerPass(new UserProviderCompilerPass());
    }

}
