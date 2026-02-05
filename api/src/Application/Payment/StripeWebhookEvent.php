<?php

namespace App\Application\Payment;

final class StripeWebhookEvent
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $userId,
        public readonly ?string $sessionId
    ) {}
}
