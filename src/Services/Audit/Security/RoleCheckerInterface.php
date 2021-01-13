<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\Security;

interface RoleCheckerInterface
{
    public function __invoke(string $entity, string $scope): bool;
}
