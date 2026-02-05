<?php

namespace App\Domain\Payment\Exception;

final class StripeWebhookInvalidSignatureException extends \RuntimeException
{
    public function __construct(string $message = 'Invalid Stripe webhook signature.')
    {
        parent::__construct($message);
    }
}
