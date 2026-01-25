<?php
namespace App\Infrastructure\Logo;

use App\Application\Logo\ExternalLogoProviderInterface;
use App\Domain\Logo\Entity\Logo;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class LogoDevApiAdapter implements ExternalLogoProviderInterface
{
    public function fetchLogos(): array
    {
        $companies = [
            'google.com',
            'apple.com',
            'sncf.com',
            'tourdumondiste.com',
            'instagram.com',
            'netflix.com',
            'spotify.com',
            'airbnb.com',
            'stripe.com',
            'esgi.fr'
        ];

        $logos = [];

        foreach ($companies as $domain) {
            $imageUrl = sprintf(
                'https://img.logo.dev/%s',
                $domain
            );

            $logos[] = new Logo(
                company: $domain,
                imageURL: $imageUrl
            );
        }

        return $logos;
    }
}
