<?php

namespace App\Domain\TierList\ValueObject;

final class TierListSnapshot
{
    /** @param TierSnapshot[] $tiers */
    public function __construct(
        public readonly string $title,
        public readonly string $playerDate,
        public readonly array $tiers
    ) {}
}
