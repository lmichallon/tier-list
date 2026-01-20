<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Logo\Entity\Logo;
use App\Domain\Logo\Repository\LogoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class LogoRepository implements LogoRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(Logo $logo): void
    {
        $this->entityManager->persist($logo);
        $this->entityManager->flush();
    }

    public function existsByCompany(string $company): bool
    {
        return (bool) $this->entityManager
            ->getRepository(Logo::class)
            ->findOneBy(['company' => $company]);
    }

    public function count(): int
    {
        return (int) $this->entityManager
            ->getRepository(Logo::class)
            ->count([]);
    }
}
