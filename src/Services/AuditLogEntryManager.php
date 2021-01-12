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
        $urlAdded = $event->getPayload();
        $urlAdded['url'] = $this->requestStack->getCurrentRequest()->getUri();
        $event->setPayload($urlAdded);
        $body = $this->autoMapper->map($event->getPayload(), AuditLogEntryJsonldAuditLogWrite::class);
//        $body = new AuditLogEntryJsonldAuditLogWrite();
//        $uri = $this->requestStack->getCurrentRequest()->getUri();
//        $body->setEntryType($event->getPayload()['type']);
//        $body->setUserUuid($event->getPayload()['blame_id']);
//        $body->setBody([$event->getPayload()['diffs'], $event->getPayload()['discriminator']]);
//
//        $body->setUrl($uri);
        $this->auditLogEntryApi->postAuditLogEntryCollection($body);
    }

}
