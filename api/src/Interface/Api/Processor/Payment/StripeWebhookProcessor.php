<?php

namespace App\Interface\Api\Processor\Payment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Payment\HandleStripeWebhook;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class StripeWebhookProcessor implements ProcessorInterface
{
    public function __construct(
        private HandleStripeWebhook $handleStripeWebhook,
        private RequestStack $requestStack
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return new Response(null, 400);
        }

        $payload = (string) $request->getContent();
        $signature = (string) $request->headers->get('Stripe-Signature', '');

        $this->handleStripeWebhook->handle($payload, $signature);

        return new Response(null, 200);
    }
}
