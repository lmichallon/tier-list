<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function existsByEmail(string $email): bool;

    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;
}
