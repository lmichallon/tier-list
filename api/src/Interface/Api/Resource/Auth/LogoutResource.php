<?php

namespace App\Interface\Api\Resource\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Processor\Auth\LogoutProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/auth/logout',
            processor: LogoutProcessor::class,
            output: false,
            deserialize: false
        )
    ]
)]
final class LogoutResource
{
}
