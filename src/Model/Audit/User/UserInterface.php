<?php

namespace VentureLeap\LeapOnePhpSdk\Model\Audit\User;

interface UserInterface
{
    public function getIdentifier(): ?string;

    public function getUsername(): ?string;
}
