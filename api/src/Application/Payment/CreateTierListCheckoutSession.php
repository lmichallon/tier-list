<?php

namespace App\Application\Payment;

use App\Domain\User\Entity\User;

final class CreateTierListCheckoutSession
{
    public function __construct(
        private StripeGatewayInterface $stripeGateway
    ) {}

    public function execute(User $user): CheckoutSession
    {
        return $this->stripeGateway->createTierListCheckoutSession($user);
    }
}
