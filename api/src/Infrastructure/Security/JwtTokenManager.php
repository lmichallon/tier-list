<?php

namespace App\Infrastructure\Security;

use App\Application\Security\TokenManagerInterface;
use App\Domain\Auth\Exception\InvalidTokenException;
use App\Domain\User\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class JwtTokenManager implements TokenManagerInterface
{
    public function __construct(
        #[Autowire('%env(string:JWT_SECRET)%')]
        private string $secret,
        #[Autowire('%env(int:JWT_TTL)%')]
        private int $ttl
    ) {}

    public function createAccessToken(User $user): string
    {
        $now = new \DateTimeImmutable();
        $payload = [
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'iat' => $now->getTimestamp(),
            'exp' => $now->getTimestamp() + $this->ttl,
        ];

        return $this->encode($payload);
    }

    public function parse(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new InvalidTokenException('Malformed access token.');
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $headerJson = $this->base64UrlDecode($encodedHeader);
        $payloadJson = $this->base64UrlDecode($encodedPayload);

        try {
            $header = json_decode($headerJson, true, 512, JSON_THROW_ON_ERROR);
            $payload = json_decode($payloadJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new InvalidTokenException('Malformed token payload.');
        }

        if (($header['alg'] ?? null) !== 'HS256') {
            throw new InvalidTokenException('Unsupported token algorithm.');
        }

        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secret, true)
        );

        if (!hash_equals($expectedSignature, $encodedSignature)) {
            throw new InvalidTokenException('Invalid token signature.');
        }

        $exp = $payload['exp'] ?? null;
        if (!is_int($exp)) {
            if (is_numeric($exp)) {
                $exp = (int) $exp;
            } else {
                throw new InvalidTokenException('Invalid token expiration.');
            }
        }

        if ($exp < time()) {
            throw new InvalidTokenException('Token expired.');
        }

        if (!isset($payload['email']) || !is_string($payload['email'])) {
            throw new InvalidTokenException('Token missing subject.');
        }

        return $payload;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    /** @param array<string, mixed> $payload */
    private function encode(array $payload): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));

        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secret, true);
        $encodedSignature = $this->base64UrlEncode($signature);

        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        $padded = strtr($data, '-_', '+/');
        $padded .= str_repeat('=', (4 - (strlen($padded) % 4)) % 4);

        $decoded = base64_decode($padded, true);
        if ($decoded === false) {
            throw new InvalidTokenException('Malformed token encoding.');
        }

        return $decoded;
    }
}
