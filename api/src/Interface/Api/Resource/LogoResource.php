<?php

namespace App\Interface\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Processor\AddLogoProcessor;
use App\Interface\Api\Provider\LogoCollectionProvider;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/logos',
            provider: LogoCollectionProvider::class,
            security: 'is_granted("PUBLIC_ACCESS")'
        )
    ]
)]
final class LogoResource
{
    public string $id;
    public string $company;
    public string $imageURL;
}
