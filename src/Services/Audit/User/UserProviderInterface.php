<?php

namespace VentureLeap\LeapOnePhpSdk\Model\Audit\User;

interface UserProviderInterface
{
    public function __invoke(): ?UserInterface;
}
