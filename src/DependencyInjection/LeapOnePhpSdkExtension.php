<?php


namespace VentureLeap\LeapOnePhpSdk\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use VentureLeap\LeapOnePhpSdk\Controller\UserController;

class LeapOnePhpSdkExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('leap_one_sdk_route_after_login', $config[Configuration::ROUTE_AFTER_LOGIN_KEY]);
    }
}
