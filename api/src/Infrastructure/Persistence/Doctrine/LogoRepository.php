<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Logo\Entity\Logo;
use App\Domain\Logo\Repository\LogoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class LogoRepository implements LogoRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function find(string $id): Logo
    {
        $logo = $this->entityManager
            ->getRepository(Logo::class)
            ->find($id);

        if (!$logo) {
            throw new \RuntimeException(sprintf('Logo %s not found', $id));
        }

        return $logo;
    }

    /**
     * @return Logo[]
     */
    public function findAll(): array
    {
        return $this->entityManager
            ->getRepository(Logo::class)
            ->findAll();
    }

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
