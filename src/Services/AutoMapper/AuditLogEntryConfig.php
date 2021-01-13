<?php


namespace VentureLeap\LeapOnePhpSdk\Services\AutoMapper;


use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\AutoMapperPlusBundle\src\Services\AutoMapper\AuditLogEntryMapper;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\DataType;
use VentureLeap\AuditLogService\Model\AuditLogEntryJsonldAuditLogWrite;

class AuditLogEntryConfig implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(DataType::ARRAY, AuditLogEntryJsonldAuditLogWrite::class)
            ->useCustomMapper(new AuditLogEntryMapper());
    }
}
