<?php

namespace App\Interface\Api\EventSubscriber;

use App\Domain\Logo\Exception\LogoAlreadyExistsException;
use App\Domain\Logo\Exception\LogoLimitReachedException;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\Auth\Exception\InvalidRefreshTokenException;
use App\Domain\Auth\Exception\InvalidTokenException;
use App\Domain\Payment\Exception\StripeWebhookInvalidSignatureException;
use App\Domain\TierList\Exception\PdfGenerationFailedException;
use App\Domain\TierList\Exception\TierListPaymentRequiredException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return ['kernel.exception' => 'onException'];
    }

    public function onException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        match (true) {
            $e instanceof LogoAlreadyExistsException =>
            $event->setResponse(new JsonResponse(null, 409)),

            $e instanceof LogoLimitReachedException =>
            $event->setResponse(new JsonResponse(null, 400)),

            $e instanceof UserAlreadyExistsException =>
            $event->setResponse(new JsonResponse(null, 409)),

            $e instanceof InvalidCredentialsException =>
            $event->setResponse(new JsonResponse(null, 401)),

            $e instanceof InvalidRefreshTokenException =>
            $event->setResponse(new JsonResponse(null, 401)),

            $e instanceof InvalidTokenException =>
            $event->setResponse(new JsonResponse(null, 401)),

            $e instanceof PdfGenerationFailedException =>
            $event->setResponse(new JsonResponse(null, 500)),

            $e instanceof TierListPaymentRequiredException =>
            $event->setResponse(new JsonResponse(null, 402)),

            $e instanceof StripeWebhookInvalidSignatureException =>
            $event->setResponse(new JsonResponse(null, 400)),

            default => null
        };
    }
}
