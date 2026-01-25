<?php

namespace App\Application\Auth;

final class AuthTokens
{
    public function __construct(
        public readonly string $accessToken,
        public readonly int $accessTokenExpiresIn,
        public readonly string $refreshToken,
        public readonly \DateTimeImmutable $refreshTokenExpiresAt
    ) {}
}
