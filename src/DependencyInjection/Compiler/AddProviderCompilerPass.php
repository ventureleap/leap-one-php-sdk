<?php

namespace VentureLeap\LeapOnePhpSdk\DependencyInjection\Compiler;

use DH\Auditor\Auditor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddProviderCompilerPass implements CompilerPassInterface
{
    /**
     * Get all providers based on their tag (`leap_one_php_sdk.provider`) and register them.
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(Auditor::class)) {
            return;
        }

        $auditorDefinition = $container->getDefinition(Auditor::class);

        $providers = [];
        foreach ($container->findTaggedServiceIds('leap_one_php_sdk.provider') as $providerId => $attributes) {
            $providers[] = new Reference($providerId);
        }

        foreach ($providers as $provider) {
            $auditorDefinition->addMethodCall('registerProvider', [$provider]);
        }
    }
}
