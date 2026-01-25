<?php

namespace App\Domain\TierList\ValueObject;

final class LogoSnapshot
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $imageUrl
    ) {}
}
