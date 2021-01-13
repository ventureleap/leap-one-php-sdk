<?php

namespace VentureLeap\LeapOnePhpSdk\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use VentureLeap\LeapOnePhpSdk\Services\Annotation\AnnotationLoader;
use VentureLeap\LeapOnePhpSdk\Services\Doctrine\AuditingService;
use VentureLeap\LeapOnePhpSdk\Services\Doctrine\DoctrineProvider;
use VentureLeap\LeapOnePhpSdk\Services\Doctrine\StorageService;

class StorageConfigurationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(DoctrineProvider::class)) {
            return;
        }

        $doctrineProviderConfigurationKey = 'leap_one_php_sdk.provider.doctrine.configuration';
        if (!$container->hasParameter($doctrineProviderConfigurationKey)) {
            return;
        }

        $providerDefinition = $container->getDefinition(DoctrineProvider::class);
        $config = $container->getParameter($doctrineProviderConfigurationKey);
//        foreach ($config['storage_services'] as $service) {
//            $service = str_replace('@', '', $service);
//            $entityManagerReference = new Reference($service);
//
//            $serviceDefinition = new Definition(StorageService::class, [
//                'leap_one_php_sdk.provider.doctrine.storage_services.'.$service,
//                $entityManagerReference,
//            ]);
//            $container->setDefinition(StorageService::class, $serviceDefinition);
//            $serviceReference = new Reference(StorageService::class);
//
//            $providerDefinition->addMethodCall('registerStorageService', [$serviceReference]);
//        }

        foreach ($config['auditing_services'] as $service) {
            $service = str_replace('@', '', $service);
            $entityManagerReference = new Reference($service);

            $serviceDefinition = new Definition(AuditingService::class, [
                'leap_one_php_sdk.provider.doctrine.auditing_services.'.$service,
                $entityManagerReference,
            ]);
            $container->setDefinition(AuditingService::class, $serviceDefinition);
            $serviceReference = new Reference(AuditingService::class);

            $annotationLoaderDefinition = new Definition(AnnotationLoader::class, [$entityManagerReference]);
            $container->setDefinition(AnnotationLoader::class, $annotationLoaderDefinition);

            $providerDefinition->addMethodCall('registerAuditingService', [$serviceReference]);
        }
    }
}
