<?php

namespace App\Application\Payment;

use App\Domain\User\Repository\UserRepositoryInterface;

final class HandleStripeWebhook
{
    public function __construct(
        private StripeGatewayInterface $stripeGateway,
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(string $payload, string $signature): void
    {
        $event = $this->stripeGateway->parseWebhookEvent($payload, $signature);

        if ($event->type !== 'checkout.session.completed') {
            return;
        }

        if (!$event->userId) {
            return;
        }

        $user = $this->userRepository->findById($event->userId);
        if (!$user) {
            return;
        }

        if ($user->hasTierListAccess()) {
            return;
        }

        $user->grantTierListAccess();
        $this->userRepository->save($user);
    }
}
