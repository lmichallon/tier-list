<?php

namespace App\Domain\TierList\Repository;

use App\Domain\TierList\Entity\TierList;
use App\Domain\TierList\Entity\TierListItem;
use App\Domain\User\Entity\User;

interface TierListRepositoryInterface
{
    public function findByUser(User $user): ?TierList;

    public function save(TierList $tierList): void;

    /**
     * @return TierListItem[]
     */
    public function findAllItems(): array;
}
