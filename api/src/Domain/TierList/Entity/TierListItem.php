<?php

namespace App\Domain\TierList\Entity;

use App\Domain\Logo\Entity\Logo;
use App\Domain\TierList\ValueObject\Tier;

final class TierListItem
{
    public function __construct(
        private Logo $logo,
        private Tier $tier
    )
    {
    }

    public function logo(): Logo
    {
        return $this->logo;
    }

    public function tier(): Tier
    {
        return $this->tier;
    }

    public function moveTo(Tier $tier): void
    {
        $this->tier = $tier;
    }
}
