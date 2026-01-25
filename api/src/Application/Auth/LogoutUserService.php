<?php

namespace App\Application\Auth;

use App\Application\Security\RefreshTokenGeneratorInterface;
use App\Domain\Auth\Repository\RefreshTokenRepositoryInterface;

final class LogoutUserService
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository,
        private RefreshTokenGeneratorInterface $refreshTokenGenerator
    ) {}

    public function logout(?string $refreshTokenValue): void
    {
        if ($refreshTokenValue === null || $refreshTokenValue === '') {
            return;
        }

        $tokenHash = $this->refreshTokenGenerator->hash($refreshTokenValue);
        $storedToken = $this->refreshTokenRepository->findByHash($tokenHash);

        if ($storedToken === null) {
            return;
        }

        $storedToken->revoke();
        $this->refreshTokenRepository->save($storedToken);
    }
}
