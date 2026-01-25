<?php

namespace App\Application\Security;

final class GeneratedRefreshToken
{
    public function __construct(
        public readonly string $plainToken,
        public readonly string $tokenHash
    ) {}
}
