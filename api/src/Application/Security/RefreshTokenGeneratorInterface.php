<?php

namespace App\Application\Security;

interface RefreshTokenGeneratorInterface
{
    public function generate(): GeneratedRefreshToken;

    public function hash(string $token): string;
}
