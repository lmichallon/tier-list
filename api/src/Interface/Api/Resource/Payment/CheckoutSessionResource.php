<?php

namespace App\Interface\Api\Resource\Payment;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Processor\Payment\CreateCheckoutSessionProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/payments/checkout',
            processor: CreateCheckoutSessionProcessor::class,
            input: false,
            output: false
        )
    ]
)]
final class CheckoutSessionResource
{
}
