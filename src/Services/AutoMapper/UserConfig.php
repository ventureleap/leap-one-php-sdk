<?php


namespace VentureLeap\LeapOnePhpSdk\Services\AutoMapper;


use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use VentureLeap\LeapOnePhpSdk\Model\User\User;
use VentureLeap\UserService\Model\UserJsonldUserRead;
use VentureLeap\UserService\Model\UserJsonldUserWrite;

class UserConfig implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(UserJsonldUserRead::class, User::class)
            ->forMember(
                'uuid',
                function (UserJsonldUserRead $source) {
                    return $source->getUuid();
                }
            )
            ->forMember(
                'firstName',
                function (UserJsonldUserRead $source) {
                    return $source->getFirstName();
                }
            )
            ->forMember(
                'lastName',
                function (UserJsonldUserRead $source) {
                    return $source->getLastName();
                }
            )
            ->forMember(
                'email',
                function (UserJsonldUserRead $source) {
                    return $source->getEmail();
                }
            )
            ->forMember(
                'username',
                function (UserJsonldUserRead $source) {
                    return $source->getUsername();
                }
            )
            ->forMember(
                'userType',
                function (UserJsonldUserRead $source) {
                    return $source->getUserType();
                }
            )
            ->forMember(
                'roles',
                function (UserJsonldUserRead $source) {
                    return $source->getRoles();
                }
            )
            ->forMember(
                'additionalProperties',
                function (UserJsonldUserRead $source) {
                    return json_decode($source->getAdditionalProperties(), true);
                }
            )
            ->forMember(
                'active',
                function (UserJsonldUserRead $source) {
                    return $source->getActive();
                }
            )
            ->forMember(
                'createdAt',
                function (UserJsonldUserRead $source) {
                    return $source->getCreatedAt();
                }
            );

        $config->registerMapping(\VentureLeap\UserService\Model\User::class, User::class)
            ->forMember(
                'uuid',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getUuid();
                }
            )
            ->forMember(
                'firstName',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getFirstName();
                }
            )
            ->forMember(
                'lastName',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getLastName();
                }
            )
            ->forMember(
                'email',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getEmail();
                }
            )
            ->forMember(
                'username',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getUsername();
                }
            )
            ->forMember(
                'userType',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getUserType();
                }
            )
            ->forMember(
                'roles',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getRoles();
                }
            )
            ->forMember(
                'additionalProperties',
                function (\VentureLeap\UserService\Model\User $source) {
                    return json_decode($source->getAdditionalProperties(), true);
                }
            )
            ->forMember(
                'active',
                function (\VentureLeap\UserService\Model\User $source) {
                    return $source->getActive();
                }
            );

        /**
         * @TODO This array thing does not work, because we have
         * to update it all the time. Let's use the normal callback style instead.
         */
        $config->registerMapping(User::class, UserJsonldUserWrite::class)
            ->forMember(
                'container',
                function (User $source) {
                    return [
                        'context' => '',
                        'id' => null,
                        'account' => null,
                        'type' => '',
                        'auth_code' => null,
                        'date_of_birth' => null,
                        'failed_login_attempts' => null,
                        'failed_login_time' => null,
                        'gender' => null,
                        'addresses' => [],
                        'email' => $source->getEmail(),
                        'username' => $source->getUsername(),
                        'password' => $source->getPlainPassword(),
                        'encoded_password' => $source->getPassword(),
                        'first_name' => $source->getFirstName(),
                        'last_name' => $source->getLastName(),
                        'deleted' => $source->isDeleted(),
                        'active' => $source->isActive(),
                        'roles' => $source->getRoles(),
                        'user_type' => $source->getUserType(),
                        'additional_properties' => json_encode($source->getAdditionalProperties()),
                    ];
                }
            );
    }
}
