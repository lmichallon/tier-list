<?php

namespace App\Interface\Api\EventSubscriber;

use App\Domain\Logo\Exception\LogoAlreadyExistsException;
use App\Domain\Logo\Exception\LogoLimitReachedException;
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

            default => null
        };
    }
}
