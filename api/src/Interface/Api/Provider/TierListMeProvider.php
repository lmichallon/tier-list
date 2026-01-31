<?php

namespace App\Interface\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Domain\TierList\Repository\TierListRepositoryInterface;
use App\Domain\TierList\ValueObject\Tier;
use Symfony\Bundle\SecurityBundle\Security;


final class TierListMeProvider implements ProviderInterface
{
    public function __construct(
        private TierListRepositoryInterface $tierListRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?array
    {
        $user = $this->security->getUser();
        if (!$user) {
            return null;
        }

        $tierList = $this->tierListRepository->findByUser($user);

        if (!$tierList) {
            return [
                'tiers' => [],
            ];
        }

        $result = [
            'unranked' => [],
            'S' => [],
            'A' => [],
            'B' => [],
            'C' => [],
            'D' => [],
        ];

        foreach ($tierList->items() as $item) {
            $result[$item->tier()->value][] = [
                'id' => $item->logo()->getId(),
                'name' => $item->logo()->getCompany(),
                'imageUrl' => $item->logo()->getImageURL(),
            ];
        }

        return [
            'tiers' => $result,
        ];
    }
}
