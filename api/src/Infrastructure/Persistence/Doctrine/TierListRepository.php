<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\TierList\Entity\TierList;
use App\Domain\TierList\Repository\TierListRepositoryInterface;
use App\Domain\TierList\ValueObject\Tier;
use App\Domain\User\Entity\User;
use App\Infrastructure\Persistence\Doctrine\Entity\TierListDoctrine;
use Doctrine\ORM\EntityManagerInterface;

final class TierListRepository implements TierListRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function findByUser(User $user): ?TierList
    {
        $tierListDoctrine = $this->entityManager
            ->getRepository(TierListDoctrine::class)
            ->findOneBy(['user' => $user]);

        if (!$tierListDoctrine) {
            return null;
        }

        return $this->mapToDomain($tierListDoctrine);
    }

    public function save(TierList $tierList): void
    {
        $repo = $this->entityManager->getRepository(TierListDoctrine::class);

        $tierListDoctrine = $repo->findOneBy([
            'user' => $tierList->user(),
        ]);

        if (!$tierListDoctrine) {
            $tierListDoctrine = new TierListDoctrine($tierList->user());
            $this->entityManager->persist($tierListDoctrine);
        }

        foreach ($tierListDoctrine->items() as $item) {
            $tierListDoctrine->removeItem($item);
        }

        foreach ($tierList->items() as $item) {
            $tierListDoctrine->addLogo(
                $item->logo(),
                $item->tier()->value
            );
        }

        $this->entityManager->flush();
    }

    private function mapToDomain(TierListDoctrine $entity): TierList
    {
        $tierList = new TierList($entity->user());

        foreach ($entity->items() as $item) {
            $tierList->moveLogo(
                $item->logo(),
                Tier::from($item->tier())
            );
        }

        return $tierList;
    }

    private function mapToDoctrine(TierList $tierList): TierListDoctrine
    {
        $tierListDoctrine = new TierListDoctrine($tierList->user());

        foreach ($tierList->items() as $item) {
            $tierListDoctrine->addLogo(
                $item->logo(),
                $item->tier()->value
            );
        }

        return $tierListDoctrine;
    }
}
