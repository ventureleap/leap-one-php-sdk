<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\User;

interface UserInterface
{
    public function getIdentifier(): ?string;

    public function getUsername(): ?string;
}
