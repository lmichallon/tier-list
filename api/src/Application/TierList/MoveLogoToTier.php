<?php

namespace App\Application\TierList;

use App\Domain\TierList\Repository\TierListRepositoryInterface;
use App\Domain\TierList\Entity\TierList;
use App\Domain\TierList\ValueObject\Tier;
use App\Domain\Logo\Repository\LogoRepositoryInterface;
use App\Domain\User\Entity\User;

final class MoveLogoToTier
{
    public function __construct(
        private TierListRepositoryInterface $tierListRepository,
        private LogoRepositoryInterface     $logoRepository
    )
    {
    }

    public function execute(User $user, string $logoId, Tier $tier): void
    {
        $tierList = $this->tierListRepository->findByUser($user)
            ?? new TierList($user);

        $logo = $this->logoRepository->find($logoId);

        $tierList->moveLogo($logo, $tier);

        $this->tierListRepository->save($tierList);
    }
}
