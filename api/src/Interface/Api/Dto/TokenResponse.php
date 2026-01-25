<?php

namespace App\Interface\Api\Dto;

final class TokenResponse
{
    public function __construct(
        public string $accessToken,
        public string $tokenType,
        public int $expiresIn
    ) {}
}
