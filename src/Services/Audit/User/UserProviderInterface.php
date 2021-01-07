<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\User;

interface UserProviderInterface
{
    public function __invoke(): ?UserInterface;
}
