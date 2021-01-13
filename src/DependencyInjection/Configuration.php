<?php


namespace VentureLeap\LeapOnePhpSdk\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const ROUTE_AFTER_LOGIN_KEY = 'route_after_login';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('leap_one_php_sdk');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->variableNode(static::ROUTE_AFTER_LOGIN_KEY)->defaultValue('account_profile_show')->end()
            ->booleanNode('enabled')
            ->defaultTrue()
            ->end()
            ->scalarNode('timezone')
            ->defaultValue('UTC')
            ->end()
            ->scalarNode('user_provider')
            ->defaultValue('leap_one_php_sdk.user_provider')
            ->end()
            ->scalarNode('security_provider')
            ->defaultValue('leap_one_php_sdk.security_provider')
            ->end()
            ->scalarNode('role_checker')
            ->defaultValue('leap_one_php_sdk.role_checker')
            ->end()
            ->append($this->getProvidersNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * Proxy to get root node for Symfony < 4.2.
     */
    protected function getRootNode(TreeBuilder $treeBuilder, string $name): ArrayNodeDefinition
    {
        if (method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->getRootNode();
        }

        return $treeBuilder->root($name);
    }

    private function getProvidersNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('providers');

        return $this->getRootNode($treeBuilder, 'providers')
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->variablePrototype()
            ->validate()
            ->ifEmpty()
            ->thenInvalid('Invalid provider configuration %s')
            ->end()
            ->end()

            ->validate()
            ->always()
            ->then(function ($v) {
                if (!\array_key_exists('doctrine', $v)) {
                    $v['doctrine'] = [];
                }

                // "entities" are "enabled" by default.
                if (\array_key_exists('entities', $v['doctrine']) && \is_array($v['doctrine']['entities'])) {
                    foreach ($v['doctrine']['entities'] as $entity => $options) {
                        if (null === $options || !\array_key_exists('enabled', $options)) {
                            $v['doctrine']['entities'][$entity]['enabled'] = true;
                        }
                    }
                }

                // "doctrine.orm.default_entity_manager" is the default "storage_services"
                if (\array_key_exists('storage_services', $v['doctrine']) && \is_string($v['doctrine']['storage_services'])) {
                    $v['doctrine']['storage_services'] = [$v['doctrine']['storage_services']];
                } elseif (!\array_key_exists('storage_services', $v['doctrine']) || !\is_array($v['doctrine']['storage_services'])) {
                    $v['doctrine']['storage_services'] = ['doctrine.orm.default_entity_manager'];
                }

                // "doctrine.orm.default_entity_manager" is the default "auditing_services"
                if (\array_key_exists('auditing_services', $v['doctrine']) && \is_string($v['doctrine']['auditing_services'])) {
                    $v['doctrine']['auditing_services'] = [$v['doctrine']['auditing_services']];
                } elseif (!\array_key_exists('auditing_services', $v['doctrine']) || !\is_array($v['doctrine']['auditing_services'])) {
                    $v['doctrine']['auditing_services'] = ['doctrine.orm.default_entity_manager'];
                }

                // "viewer" is enabled by default
                if (!\array_key_exists('viewer', $v['doctrine']) || !\is_bool($v['doctrine']['viewer'])) {
                    $v['doctrine']['viewer'] = true;
                }

                // "storage_mapper" is null by default
                if (!\array_key_exists('storage_mapper', $v['doctrine']) || !\is_string($v['doctrine']['storage_mapper'])) {
                    $v['doctrine']['storage_mapper'] = null;
                }

                return $v;
            })
            ->end()
            ;
    }
}
