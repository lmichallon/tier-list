<?php

namespace App\Interface\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Processor\AddLogoProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/logos',
            processor: AddLogoProcessor::class
        )
    ]
)]
final class LogoResource
{
    public string $company;
    public string $imageURL;
}
