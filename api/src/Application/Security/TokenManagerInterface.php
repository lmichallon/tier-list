<?php

namespace App\Application\Security;

use App\Domain\User\Entity\User;

interface TokenManagerInterface
{
    public function createAccessToken(User $user): string;

    /** @return array<string, mixed> */
    public function parse(string $token): array;

    public function getTtl(): int;
}
