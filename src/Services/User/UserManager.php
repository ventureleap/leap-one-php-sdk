<?php


namespace VentureLeap\LeapOnePhpSdk\Services\User;


use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use VentureLeap\LeapOnePhpSdk\Model\User\User;
use VentureLeap\UserService\Api\UserApi;
use VentureLeap\UserService\Model\UserJsonldUserRead;
use VentureLeap\UserService\Model\UserJsonldUserWrite;

class UserManager implements UserManagerInterface
{
    /**
     * @var UserApi
     */
    private $userApi;
    /**
     * @var AutoMapperInterface
     */
    private $autoMapper;

    public function __construct(UserApi $userApi, AutoMapperInterface $autoMapper)
    {
        $this->userApi = $userApi;
        $this->autoMapper = $autoMapper;
    }

    public function registerUser(User $leapOneUser): User
    {
        $leapOneApiUser = $this->autoMapper->map($leapOneUser, UserJsonldUserWrite::class);
        /**
         * @TODO Call the real register route once it is ready.
         */
        $leapOneApiUserResponse = $this->userApi->postUserCollection(
            $leapOneApiUser
        );
        $leapOneUser->setUuid($leapOneApiUserResponse->getUuid());

        return $leapOneUser;
    }

    public function getUserByUuid(string $uuid): User
    {
        $leapOneApiUser = $this->userApi->getUserItem(
            $uuid
        );

        return $this->autoMapper->map($leapOneApiUser, User::class);
    }

    public function updateUser(User $leapOneUser): void
    {
        $leapOneApiUser = $this->autoMapper->map($leapOneUser, UserJsonldUserWrite::class);
        $this->userApi->putUserItem($leapOneUser->getUuid(), $leapOneApiUser);
    }

    public function getUserByUsername(string $username): User
    {
        $usersForUsername = $this->userApi->getUserCollection($username);

        $user = $usersForUsername->getHydramember()[0] ?? null;
        if (null === $user) {
            throw new \Exception('User not found');
        }
        $uuid = $user->getUuid();

        return $this->getUserByUuid($uuid);
    }

    public function isPasswordValid(UserInterface $user, $password): bool
    {
        /**
         * @TODO Here we would have to use a different API,
         * but I suggest we just put it the user API as well.
         * For now, we just return true always :-)
         */
        return true;
    }
}