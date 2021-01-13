<?php


namespace VentureLeap\LeapOnePhpSdk\Services\User;


use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use VentureLeap\LeapOnePhpSdk\Model\User\User;
use VentureLeap\UserService\Api\UserApi;

class UserProvider implements UserProviderInterface
{

    const DEFAULT_USER_TYPE = 'admin';

    /**
     * @var UserApi
     */
    private $userApi;
    /**
     * @var AutoMapperInterface
     */
    private $autoMapper;
    private $userType;


    public function __construct(
        AutoMapperInterface $autoMapper,
        UserApi $userApi,
        string $userType = self::DEFAULT_USER_TYPE
    ) {
        $this->userApi = $userApi;
        $this->autoMapper = $autoMapper;
        $this->userType = $userType;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function loadUserByUsername($username): ?User
    {
        $usersForUsername = $this->userApi->getUserCollection($username, null, null, null, $this->userType);

        if (null === $usersForUsername || null === $usersForUsername->getHydramember()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        $leapOneUser = null;
        foreach ($usersForUsername->getHydramember() as $user) {
            if ($user->getUsername() === $username) {
                $leapOneUser = $user;
            }
        }

        if (null === $leapOneUser) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $this->autoMapper->map($leapOneUser, User::class);
    }

    public function getUser(string $uuid): ?User
    {
        $leapOneUser = $this->userApi->getUserItem($uuid);

        return $this->autoMapper->map($leapOneUser, User::class);
    }

    public function refreshUser(UserInterface $user): ?User
    {

        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
