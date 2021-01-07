<?php

namespace VentureLeap\LeapOnePhpSdk\Event;

use Symfony\Contracts\EventDispatcher\Event as ContractsEvent;

abstract class AuditEvent extends ContractsEvent
{
    /**
     * @var array
     */
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    final public function setPayload(array $payload): ContractsEvent
    {
        $this->payload = $payload;
        return $this;
    }

    final public function getPayload(): array
    {
        return $this->payload;
    }
}
