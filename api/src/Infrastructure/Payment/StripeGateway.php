<?php

namespace App\Infrastructure\Payment;

use App\Application\Payment\CheckoutSession;
use App\Application\Payment\StripeGatewayInterface;
use App\Application\Payment\StripeWebhookEvent;
use App\Domain\Payment\Exception\StripeWebhookInvalidSignatureException;
use App\Domain\User\Entity\User;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class StripeGateway implements StripeGatewayInterface
{
    public function __construct(
        #[Autowire('%env(STRIPE_SECRET_KEY)%')]
        private string $secretKey,
        #[Autowire('%env(STRIPE_WEBHOOK_SECRET)%')]
        private string $webhookSecret,
        #[Autowire('%env(STRIPE_PRICE_ID)%')]
        private string $priceId,
        #[Autowire('%env(STRIPE_SUCCESS_URL)%')]
        private string $successUrl,
        #[Autowire('%env(STRIPE_CANCEL_URL)%')]
        private string $cancelUrl
    ) {}

    public function createTierListCheckoutSession(User $user): CheckoutSession
    {
        Stripe::setApiKey($this->secretKey);

        $session = Session::create([
            'mode' => 'payment',
            'customer_email' => $user->getEmail(),
            'client_reference_id' => $user->getId(),
            'line_items' => [
                [
                    'price' => $this->priceId,
                    'quantity' => 1,
                ],
            ],
            'success_url' => $this->successUrl,
            'cancel_url' => $this->cancelUrl,
            'metadata' => [
                'user_id' => $user->getId(),
            ],
        ]);

        return new CheckoutSession($session->id, $session->url ?? '');
    }

    public function parseWebhookEvent(string $payload, string $signature): StripeWebhookEvent
    {
        try {
            $event = Webhook::constructEvent($payload, $signature, $this->webhookSecret);
        } catch (\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException $e) {
            throw new StripeWebhookInvalidSignatureException();
        }

        $userId = null;
        $sessionId = null;

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $sessionId = $session->id ?? null;
            $userId = $session->client_reference_id ?? null;

            if (!$userId && isset($session->metadata['user_id'])) {
                $userId = $session->metadata['user_id'];
            }
        }

        return new StripeWebhookEvent($event->type, $userId, $sessionId);
    }
}
