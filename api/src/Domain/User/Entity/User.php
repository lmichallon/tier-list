<?php

namespace App\Domain\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $passwordHash;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'boolean')]
    private bool $tierListAccessGranted = false;

    public function __construct(string $email, string $passwordHash, array $roles = ['ROLE_USER'])
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->roles = $roles;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function hasTierListAccess(): bool
    {
        return $this->tierListAccessGranted;
    }

    public function grantTierListAccess(): void
    {
        $this->tierListAccessGranted = true;
    }

    public function eraseCredentials(): void
    {
        // No transient data stored on the user.
    }
}
