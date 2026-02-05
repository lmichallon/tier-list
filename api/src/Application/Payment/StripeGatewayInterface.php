<?php

namespace App\Application\Payment;

use App\Domain\User\Entity\User;

interface StripeGatewayInterface
{
    public function createTierListCheckoutSession(User $user): CheckoutSession;

    public function parseWebhookEvent(string $payload, string $signature): StripeWebhookEvent;
}
