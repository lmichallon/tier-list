<?php

namespace App\Interface\Api\Processor\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Auth\LogoutUserService;
use App\Infrastructure\Http\RefreshTokenCookieFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class LogoutProcessor implements ProcessorInterface
{
    public function __construct(
        private LogoutUserService $logoutUserService,
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

        $this->logoutUserService->logout(is_string($refreshTokenValue) ? $refreshTokenValue : null);

        $response = new Response(null, Response::HTTP_NO_CONTENT);
        $response->headers->setCookie($this->cookieFactory->clear());

        return $response;
    }
}
