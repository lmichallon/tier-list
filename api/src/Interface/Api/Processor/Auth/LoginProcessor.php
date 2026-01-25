<?php

namespace App\Interface\Api\Processor\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Auth\LoginUserService;
use App\Infrastructure\Http\RefreshTokenCookieFactory;
use App\Interface\Api\Resource\Auth\LoginResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class LoginProcessor implements ProcessorInterface
{
    public function __construct(
        private LoginUserService $loginUserService,
        private RefreshTokenCookieFactory $cookieFactory
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Response {
        /** @var LoginResource $data */
        $tokens = $this->loginUserService->login($data->email, $data->password);

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
