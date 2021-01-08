<?php


namespace VentureLeap\LeapOnePhpSdk\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use VentureLeap\LeapOnePhpSdk\Services\Doctrine\ProviderInterface;

class LeapOnePhpSdkExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);


        $auditorConfig = $config;
        unset($auditorConfig['providers']);
        $container->setParameter('leap_one_php_sdk.configuration', $auditorConfig);

        $this->loadProviders($container, $config);


        $container->setParameter('leap_one_sdk_route_after_login', $config[Configuration::ROUTE_AFTER_LOGIN_KEY]);
    }

    private function loadProviders(ContainerBuilder $container, array $config): void
    {
        foreach ($config['providers'] as $providerName => $providerConfig) {
            $container->setParameter('leap_one_php_sdk.provider.' . $providerName . '.configuration', $providerConfig);

            if (method_exists($container, 'registerAliasForArgument')) {
                $serviceId = 'leap_one_php_sdk.provider.' . $providerName;
                $container->registerAliasForArgument($serviceId, ProviderInterface::class, "{$providerName}Provider");
            }
        }
    }
}
