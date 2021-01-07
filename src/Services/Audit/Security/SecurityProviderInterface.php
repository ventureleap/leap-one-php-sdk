<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\Security;

interface SecurityProviderInterface
{
    public function __invoke(): array;
}
