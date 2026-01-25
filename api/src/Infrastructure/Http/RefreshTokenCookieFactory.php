<?php

namespace App\Infrastructure\Http;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;

final class RefreshTokenCookieFactory
{
    public function __construct(
        #[Autowire('%env(string:REFRESH_COOKIE_NAME)%')]
        private string $cookieName,
        #[Autowire('%env(bool:REFRESH_COOKIE_SECURE)%')]
        private bool $secure,
        #[Autowire('%env(string:REFRESH_COOKIE_SAMESITE)%')]
        private string $sameSite,
        #[Autowire('%env(string:REFRESH_COOKIE_PATH)%')]
        private string $path
    ) {}

    public function create(string $token, \DateTimeImmutable $expiresAt): Cookie
    {
        return Cookie::create($this->cookieName)
            ->withValue($token)
            ->withExpires($expiresAt)
            ->withSecure($this->secure)
            ->withHttpOnly(true)
            ->withSameSite($this->sameSite)
            ->withPath($this->path);
    }

    public function clear(): Cookie
    {
        return Cookie::create($this->cookieName)
            ->withValue('')
            ->withExpires(new \DateTimeImmutable('-1 year'))
            ->withSecure($this->secure)
            ->withHttpOnly(true)
            ->withSameSite($this->sameSite)
            ->withPath($this->path);
    }

    public function getName(): string
    {
        return $this->cookieName;
    }
}
