<?php

namespace App\Infrastructure\Security;

use App\Application\Security\GeneratedRefreshToken;
use App\Application\Security\RefreshTokenGeneratorInterface;

final class RefreshTokenGenerator implements RefreshTokenGeneratorInterface
{
    public function generate(): GeneratedRefreshToken
    {
        $plainToken = rtrim(strtr(base64_encode(random_bytes(64)), '+/', '-_'), '=');
        $tokenHash = $this->hash($plainToken);

        return new GeneratedRefreshToken($plainToken, $tokenHash);
    }

    public function hash(string $token): string
    {
        return hash('sha256', $token);
    }
}
