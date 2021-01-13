<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\User;

use VentureLeap\LeapOnePhpSdk\Model\Audit\User\UserInterface;

interface UserProviderInterface
{
    public function __invoke(): ?UserInterface;
}
