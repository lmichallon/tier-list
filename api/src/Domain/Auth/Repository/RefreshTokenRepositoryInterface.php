<?php

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Entity\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function save(RefreshToken $refreshToken): void;

    public function findByHash(string $tokenHash): ?RefreshToken;

    public function findValidByHash(string $tokenHash, ?\DateTimeImmutable $now = null): ?RefreshToken;
}
