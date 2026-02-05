<?php

namespace App\Interface\Api\Resource\Payment;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Interface\Api\Provider\PaymentStatusProvider;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/payments/status',
            provider: PaymentStatusProvider::class
        )
    ]
)]
final class PaymentStatusResource
{
    public bool $paid;
}
