<?php

namespace App\Interface\Api\Processor\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Auth\RefreshTokenService;
use App\Domain\Auth\Exception\InvalidRefreshTokenException;
use App\Infrastructure\Http\RefreshTokenCookieFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class RefreshProcessor implements ProcessorInterface
{
    public function __construct(
        private RefreshTokenService $refreshTokenService,
        private RefreshTokenCookieFactory $cookieFactory,
        private RequestStack $requestStack
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Response {
        $request = $this->requestStack->getCurrentRequest();
        $refreshTokenValue = $request?->cookies->get($this->cookieFactory->getName());

        if (!is_string($refreshTokenValue) || $refreshTokenValue === '') {
            throw new InvalidRefreshTokenException();
        }

        $tokens = $this->refreshTokenService->refresh($refreshTokenValue);

        $response = new JsonResponse([
            'accessToken' => $tokens->accessToken,
            'tokenType' => 'Bearer',
            'expiresIn' => $tokens->accessTokenExpiresIn,
        ]);

        $response->headers->setCookie(
            $this->cookieFactory->create($tokens->refreshToken, $tokens->refreshTokenExpiresAt)
        );

        return $response;
    }
}
