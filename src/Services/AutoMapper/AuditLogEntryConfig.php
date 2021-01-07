<?php


namespace AutoMapperPlus\AutoMapperPlusBundle\src\Services\AutoMapper;


use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\DataType;
use AutoMapperPlus\NameConverter\NamingConvention\CamelCaseNamingConvention;
use AutoMapperPlus\NameConverter\NamingConvention\SnakeCaseNamingConvention;
use VentureLeap\AuditLogService\Model\AuditLogEntryJsonldAuditLogWrite;
use VentureLeap\LeapOnePhpSdk\Event\AuditEvent;
use VentureLeap\LeapOnePhpSdk\Event\LifecycleEvent;

class AuditLogEntryConfig implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(\VentureLeap\UserService\Model\User::class, User::class)
            ->forMember(
                'uuid',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getUuid();
                }
            )
            ->forMember(
                'firstName',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getFirstName();
                }
            )
            ->forMember(
                'lastName',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getLastName();
                }
            )
            ->forMember(
                'email',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getEmail();
                }
            )
            ->forMember(
                'username',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getUsername();
                }
            )
            ->forMember(
                'userType',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getUserType();
                }
            )
            ->forMember(
                'roles',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getRoles();
                }
            )
            ->forMember(
                'additionalProperties',
                function (\VentureLeap\UserService\Model\User $source) {
                    return json_decode($source->getAdditionalProperties(), true);
                }
            )
            ->forMember(
                'active',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getActive();
                }
            );
//        'entity' => $data['entity'],
//            'table' => $auditTable,
//            'type' => $data['action'],
//            'object_id' => (string) $data['id'],
//            'discriminator' => $data['discriminator'],
//            'transaction_hash' => (string) $data['transaction_hash'],
//            'diffs' => json_encode($data['diff']),
//            'blame_id' => $data['blame']['user_id'],
//            'blame_user' => $data['blame']['username'],
//            'blame_user_fqdn' => $data['blame']['user_fqdn'],
//            'blame_user_firewall' => $data['blame']['user_firewall'],
//            'ip' => $data['blame']['client_ip'],
//            'created_at' => $dt->format('Y-m-d H:i:s'),
        $config->registerMapping(DataType::ARRAY, AuditLogEntryJsonldAuditLogWrite::class)
            ->withNamingConventions(
                new SnakeCaseNamingConvention(),
                new CamelCaseNamingConvention()
            );
//        'user_uuid' => 'string',
//'url' => 'string',
//'body' => 'string[]',
//'entry_type' => 'string'
    }
}
