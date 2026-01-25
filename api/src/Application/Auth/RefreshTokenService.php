<?php

namespace App\Application\Auth;

use App\Application\Security\RefreshTokenGeneratorInterface;
use App\Application\Security\TokenManagerInterface;
use App\Domain\Auth\Entity\RefreshToken;
use App\Domain\Auth\Exception\InvalidRefreshTokenException;
use App\Domain\Auth\Repository\RefreshTokenRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class RefreshTokenService
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository,
        private RefreshTokenGeneratorInterface $refreshTokenGenerator,
        private TokenManagerInterface $tokenManager,
        #[Autowire('%env(int:REFRESH_TOKEN_TTL)%')]
        private int $refreshTokenTtlSeconds
    ) {}

    public function refresh(string $refreshTokenValue): AuthTokens
    {
        $tokenHash = $this->refreshTokenGenerator->hash($refreshTokenValue);
        $storedToken = $this->refreshTokenRepository->findValidByHash($tokenHash);

        if ($storedToken === null) {
            throw new InvalidRefreshTokenException();
        }

        $storedToken->revoke();
        $this->refreshTokenRepository->save($storedToken);

        $user = $storedToken->getUser();
        $accessToken = $this->tokenManager->createAccessToken($user);
        $accessTokenExpiresIn = $this->tokenManager->getTtl();

        $generatedRefreshToken = $this->refreshTokenGenerator->generate();
        $refreshTokenExpiresAt = (new \DateTimeImmutable())
            ->modify(sprintf('+%d seconds', $this->refreshTokenTtlSeconds));

        $rotatedToken = new RefreshToken(
            $user,
            $generatedRefreshToken->tokenHash,
            $refreshTokenExpiresAt
        );

        $this->refreshTokenRepository->save($rotatedToken);

        return new AuthTokens(
            $accessToken,
            $accessTokenExpiresIn,
            $generatedRefreshToken->plainToken,
            $refreshTokenExpiresAt
        );
    }
}
