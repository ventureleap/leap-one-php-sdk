<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\User;

use Exception;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use VentureLeap\LeapOnePhpSdk\Model\Audit\User\User;
use VentureLeap\LeapOnePhpSdk\Model\Audit\User\UserInterface as AuditorUserInterface;
use VentureLeap\LeapOnePhpSdk\Services\Doctrine\Configuration;

class UserProvider implements UserProviderInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Security $security, Configuration $configuration)
    {
        $this->security = $security;
        $this->configuration = $configuration;
    }

    public function __invoke(): ?AuditorUserInterface
    {
        $tokenUser = $this->getTokenUser();
        $impersonatorUser = $this->getImpersonatorUser();

        $identifier = null;
        $username = null;

        if (null !== $tokenUser && $tokenUser instanceof UserInterface) {
            if (method_exists($tokenUser, 'getUuid')) {
                $identifier = $tokenUser->getUuid();
            }

            $username = $tokenUser->getUsername();
        }

        if (null !== $impersonatorUser && $impersonatorUser instanceof UserInterface) {
            $username .= sprintf('[impersonator %s]', $impersonatorUser->getUsername());
        }

        if (null === $identifier && null === $username) {
            return null;
        }

        return new User($identifier, $username);
    }

    /**
     * @return null|UserInterface
     */
    private function getTokenUser()
    {
        try {
            $token = $this->security->getToken();
        } catch (Exception $e) {
            $token = null;
        }

        if (null === $token) {
            return null;
        }

        $tokenUser = $token->getUser();
        if ($tokenUser instanceof UserInterface) {
            return $tokenUser;
        }

        return null;
    }

    /**
     * @return null|string|UserInterface
     */
    private function getImpersonatorUser()
    {
        $token = $this->security->getToken();

        // Symfony >= 5
        if (class_exists(SwitchUserToken::class) && $token instanceof SwitchUserToken) {
            return $token->getOriginalToken()->getUser();
        }

        // Symfony < 5
        $roles = [];
        if (null !== $token) {
            $roles = method_exists($token, 'getRoleNames') ? $token->getRoleNames() : $token->getRoles();
        }

        foreach ($roles as $role) {
            if ($role instanceof SwitchUserRole) {
                return $role->getSource()->getUser();
            }
        }

        return null;
    }
}
