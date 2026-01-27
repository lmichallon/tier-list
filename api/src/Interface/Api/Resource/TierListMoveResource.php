<?php

namespace App\Interface\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Processor\MoveLogoProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/tierlists/move',
            processor: MoveLogoProcessor::class
        )
    ]
)]
final class TierListMoveResource
{
    public string $logoId;
    public string $tier;
}
