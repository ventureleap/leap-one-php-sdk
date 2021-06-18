<?php


namespace VentureLeap\LeapOnePhpSdk\Model\User;


use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    public const DEFAULT_ROLE = 'ROLE_USER';
    /**
     * @var string|null
     */
    protected $uuid;
    /**
     * @var string|null
     */
    protected $email;
    /**
     * @var string|null
     */
    protected $username;
    /**
     * @var string|null
     */
    protected $plainPassword;
    /**
     * @var string|null
     */
    protected $firstName;
    /**
     * @var string|null
     */
    protected $lastName;

    /** @var bool */
    protected $deleted;

    /** @var bool */
    protected $active;

    /** @var string */
    protected $userType;

    /** @var array */
    protected $roles;

    /** @var array */
    protected $customData;

    /** @var \DateTime */
    protected $createdAt;

    /** @var string|null */
    protected $password;

    /** @var string|null */
    protected $account;

    /** @var string|null */
    protected $phoneNumber;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }


    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {

    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }


    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    public function getCustomData(): ?array
    {
        return $this->customData;
    }

    public function setCustomData(array $customData): void
    {
        $this->customData = $customData;
    }

    public function getCustomProperty(string $propertyName)
    {
        $customData = $this->getCustomData();

        return $customData[$propertyName] ?? null;
    }

    public function setCustomProperty(string $property, $value): void
    {
        $this->customData[$property] = $value;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(?string $account): void
    {
        $this->account = $account;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
}
