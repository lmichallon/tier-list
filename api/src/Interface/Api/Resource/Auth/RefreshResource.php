<?php

namespace App\Interface\Api\Resource\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Dto\TokenResponse;
use App\Interface\Api\Processor\Auth\RefreshProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/auth/refresh',
            processor: RefreshProcessor::class,
            output: TokenResponse::class,
            deserialize: false
        )
    ]
)]
final class RefreshResource
{
}
