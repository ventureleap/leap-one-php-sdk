<?php


namespace VentureLeap\LeapOnePhpSdk\Services;


use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use VentureLeap\AuditLogService\Api\AuditLogEntryApi;
use VentureLeap\AuditLogService\Model\AuditLogEntryJsonldAuditLogWrite;
use VentureLeap\LeapOnePhpSdk\Event\LifecycleEvent;

class AuditLogEntryManager
{
    /**
     * @var AuditLogEntryApi
     */
    private $auditLogEntryApi;
    /**
     * @var AutoMapperInterface
     */
    private $autoMapper;
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(AuditLogEntryApi $auditLogEntryApi, AutoMapperInterface $autoMapper, RequestStack $requestStack)
    {
        $this->auditLogEntryApi = $auditLogEntryApi;
        $this->autoMapper = $autoMapper;
        $this->requestStack = $requestStack;
    }

    public function saveAuditLogEntry(LifecycleEvent $event)
    {
//        $body = new AuditLogEntryJsonldAuditLogWrite();
        $body = $this->autoMapper->map($event, AuditLogEntryJsonldAuditLogWrite::class);
        $this->auditLogEntryApi->postAuditLogEntryCollection($body);
        $uri = $this->requestStack->getCurrentRequest()->getUri();
        $payload = $event->getPayload();
        $auditTable = $payload['table'];
        $entity = $payload['entity'];
        unset($payload['table'], $payload['entity']);

        $fields = [
            'type' => ':type',
            'object_id' => ':object_id',
            'discriminator' => ':discriminator',
            'transaction_hash' => ':transaction_hash',
            'diffs' => ':diffs',
            'blame_id' => ':blame_id',
            'blame_user' => ':blame_user',
            'blame_user_fqdn' => ':blame_user_fqdn',
            'blame_user_firewall' => ':blame_user_firewall',
            'ip' => ':ip',
            'created_at' => ':created_at',
        ];
    }

}
