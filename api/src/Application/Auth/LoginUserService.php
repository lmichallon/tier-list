<?php

namespace App\Application\Auth;

use App\Application\Security\RefreshTokenGeneratorInterface;
use App\Application\Security\TokenManagerInterface;
use App\Application\Security\PasswordHasherInterface;
use App\Domain\Auth\Entity\RefreshToken;
use App\Domain\Auth\Repository\RefreshTokenRepositoryInterface;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class LoginUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RefreshTokenRepositoryInterface $refreshTokenRepository,
        private PasswordHasherInterface $passwordHasher,
        private TokenManagerInterface $tokenManager,
        private RefreshTokenGeneratorInterface $refreshTokenGenerator,
        #[Autowire('%env(int:REFRESH_TOKEN_TTL)%')]
        private int $refreshTokenTtlSeconds
    ) {}

    public function login(string $email, string $plainPassword): AuthTokens
    {
        $normalizedEmail = strtolower(trim($email));
        $user = $this->userRepository->findByEmail($normalizedEmail);

        if ($user === null || !$this->passwordHasher->verify($user, $plainPassword)) {
            throw new InvalidCredentialsException();
        }

        $accessToken = $this->tokenManager->createAccessToken($user);
        $accessTokenExpiresIn = $this->tokenManager->getTtl();

        $generatedRefreshToken = $this->refreshTokenGenerator->generate();
        $refreshTokenExpiresAt = (new \DateTimeImmutable())
            ->modify(sprintf('+%d seconds', $this->refreshTokenTtlSeconds));

        $refreshToken = new RefreshToken(
            $user,
            $generatedRefreshToken->tokenHash,
            $refreshTokenExpiresAt
        );

        $this->refreshTokenRepository->save($refreshToken);

        return new AuthTokens(
            $accessToken,
            $accessTokenExpiresIn,
            $generatedRefreshToken->plainToken,
            $refreshTokenExpiresAt
        );
    }
}
