<?php

namespace App\Interface\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class PaymentStatusProvider implements ProviderInterface
{
    public function __construct(
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?array
    {
        $user = $this->security->getUser();
        if (!$user) {
            return null;
        }

        return [
            'paid' => $user->hasTierListAccess(),
        ];
    }
}
