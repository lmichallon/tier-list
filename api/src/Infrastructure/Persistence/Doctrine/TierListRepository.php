<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\TierList\Entity\TierList;
use App\Domain\TierList\Repository\TierListRepositoryInterface;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class TierListRepository implements TierListRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function findByUser(User $user): ?TierList
    {
        return $this->entityManager
            ->getRepository(TierList::class)
            ->findOneBy(['user' => $user]);
    }

    public function save(TierList $tierList): void
    {
        $this->entityManager->persist($tierList);
        $this->entityManager->flush();
    }
}
