<?php

namespace App\Application\Payment;

final class CheckoutSession
{
    public function __construct(
        public readonly string $id,
        public readonly string $url
    ) {}
}
