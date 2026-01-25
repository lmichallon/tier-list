<?php

namespace App\Interface\Api\Resource\Pdf;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Processor\Pdf\GenerateTierListPdfProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/tierlists/pdf',
            processor: GenerateTierListPdfProcessor::class,
            output: false,
            deserialize: false
        )
    ]
)]
final class TierListPdfResource
{
}
