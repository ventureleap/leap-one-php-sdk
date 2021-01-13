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
        $urlAdded = $event->getPayload();
        $event->setPayload($urlAdded);
        $body = $this->autoMapper->map($event->getPayload(), AuditLogEntryJsonldAuditLogWrite::class);
        $this->auditLogEntryApi->postAuditLogEntryCollection($body);
    }

}
