<?php


namespace AutoMapperPlus\AutoMapperPlusBundle\src\Services\AutoMapper;


use AutoMapperPlus\CustomMapper\CustomMapper;
use VentureLeap\AuditLogService\Model\AuditLogEntryJsonldAuditLogWrite;

class AuditLogEntryMapper extends CustomMapper
{
    /**
     * @param array $source
     * @param AuditLogEntryJsonldAuditLogWrite $destination
     * @return mixed|void
     */
    public function mapToObject($source, $destination)
    {
        $destination->setBody($source);
        $destination->setEntryType($source['type']);
        $destination->setUrl($source['url']);
        $destination->setUserUuid($source['blame_id']);
        $destination->setContext('');
        $destination->setId(null);
        $destination->setType('');
        return $destination;
    }
}
