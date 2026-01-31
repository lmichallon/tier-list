<?php

namespace App\Interface\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Interface\Api\Provider\TierListMeProvider;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/tierlists/me',
            provider: TierListMeProvider::class
        )
    ]
)]
final class TierListMeResource
{
    public array $tiers;
}
