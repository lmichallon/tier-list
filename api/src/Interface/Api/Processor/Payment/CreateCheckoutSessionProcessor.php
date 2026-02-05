<?php

namespace App\Interface\Api\Processor\Payment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Payment\CreateTierListCheckoutSession;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateCheckoutSessionProcessor implements ProcessorInterface
{
    public function __construct(
        private CreateTierListCheckoutSession $createTierListCheckoutSession,
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(null, 401);
        }

        $session = $this->createTierListCheckoutSession->execute($user);

        return new JsonResponse([
            'id' => $session->id,
            'url' => $session->url,
        ]);
    }
}
