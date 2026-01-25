<?php

namespace App\Infrastructure\Security;

use App\Application\Security\TokenManagerInterface;
use App\Domain\Auth\Exception\InvalidTokenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class JwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private TokenManagerInterface $tokenManager
    ) {}

    public function supports(Request $request): ?bool
    {
        if ($request->isMethod('OPTIONS')) {
            return false;
        }

        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authorization = $request->headers->get('Authorization', '');

        if (!str_starts_with($authorization, 'Bearer ')) {
            throw new CustomUserMessageAuthenticationException('Missing access token.');
        }

        $token = trim(substr($authorization, 7));
        if ($token === '') {
            throw new CustomUserMessageAuthenticationException('Missing access token.');
        }

        try {
            $payload = $this->tokenManager->parse($token);
        } catch (InvalidTokenException $exception) {
            throw new CustomUserMessageAuthenticationException($exception->getMessage(), [], 0, $exception);
        }

        $email = $payload['email'] ?? null;
        if (!is_string($email) || $email === '') {
            throw new CustomUserMessageAuthenticationException('Token missing subject.');
        }

        return new SelfValidatingPassport(new UserBadge($email));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(['message' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
    }
}
