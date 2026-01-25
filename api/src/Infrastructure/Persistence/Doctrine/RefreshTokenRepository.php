<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Auth\Entity\RefreshToken;
use App\Domain\Auth\Repository\RefreshTokenRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(RefreshToken $refreshToken): void
    {
        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();
    }

    public function findByHash(string $tokenHash): ?RefreshToken
    {
        return $this->entityManager
            ->getRepository(RefreshToken::class)
            ->findOneBy(['tokenHash' => $tokenHash]);
    }

    public function findValidByHash(string $tokenHash, ?\DateTimeImmutable $now = null): ?RefreshToken
    {
        $now = $now ?? new \DateTimeImmutable();
        $qb = $this->entityManager
            ->getRepository(RefreshToken::class)
            ->createQueryBuilder('rt');

        return $qb
            ->andWhere('rt.tokenHash = :hash')
            ->andWhere('rt.revokedAt IS NULL')
            ->andWhere('rt.expiresAt > :now')
            ->setParameter('hash', $tokenHash)
            ->setParameter('now', $now)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
