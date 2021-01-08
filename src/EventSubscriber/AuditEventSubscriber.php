<?php


namespace VentureLeap\LeapOnePhpSdk\EventSubscriber;

use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use VentureLeap\AuditLogService\Model\AuditLogEntryJsonldAuditLogWrite;
use VentureLeap\LeapOnePhpSdk\Event\LifecycleEvent;
use VentureLeap\LeapOnePhpSdk\Services\Audit\Auditor;

class AuditEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Auditor
     */
    private $auditor;

    public function __construct(Auditor $auditor)
    {
        $this->auditor = $auditor;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LifecycleEvent::class => [
                ['onAuditEvent', -1000000],  // should be fired last
            ],
        ];
    }

    public function onAuditEvent(LifecycleEvent $event): LifecycleEvent
    {

        $this->auditor->getAuditLogEntryManager()->saveAuditLogEntry($event);
//        foreach ($this->auditor->getProviders() as $provider) {
//            if ($provider->supportsStorage()) {
//                try {
//                    $provider->persist($event);
//                } catch (Exception $e) {
//                    // do nothing to ensure other providers are called
//                }
//            }
//        }

        return $event;
    }
}
