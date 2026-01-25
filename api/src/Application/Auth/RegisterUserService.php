<?php

namespace App\Application\Auth;

use App\Application\Security\PasswordHasherInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Repository\UserRepositoryInterface;

final class RegisterUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher
    ) {}

    public function register(string $email, string $plainPassword): void
    {
        $normalizedEmail = strtolower(trim($email));

        if ($this->userRepository->existsByEmail($normalizedEmail)) {
            throw new UserAlreadyExistsException();
        }

        $user = new User($normalizedEmail, '');
        $passwordHash = $this->passwordHasher->hash($user, $plainPassword);
        $user->setPasswordHash($passwordHash);

        $this->userRepository->save($user);
    }
}
