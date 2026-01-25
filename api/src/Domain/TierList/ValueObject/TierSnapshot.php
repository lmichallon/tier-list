<?php

namespace App\Domain\TierList\ValueObject;

final class TierSnapshot
{
    /** @param LogoSnapshot[] $logos */
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $letter,
        public readonly string $color,
        public readonly array $logos
    ) {}
}
