<?php

namespace App\Domain\TierList\Entity;

use App\Domain\User\Entity\User;
use App\Domain\TierList\ValueObject\Tier;
use App\Domain\Logo\Entity\Logo;

final class TierList
{
    /** @var TierListItem[] */
    private array $items = [];

    public function __construct(
        private User $user
    )
    {
    }

    public function moveLogo(Logo $logo, Tier $tier): void
    {
        foreach ($this->items as $item) {
            if ($item->logo()->getId() === $logo->getId()) {
                $item->moveTo($tier);
                return;
            }
        }

        $this->items[] = new TierListItem($logo, $tier);
    }

    public function items(): array
    {
        return $this->items;
    }

    public function user(): User
    {
        return $this->user;
    }
}
