<?php

namespace App\Interface\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\TierList\MoveLogoToTier;
use App\Domain\TierList\ValueObject\Tier;
use Symfony\Bundle\SecurityBundle\Security;

final class MoveLogoProcessor implements ProcessorInterface
{
    public function __construct(
        private MoveLogoToTier $useCase,
        private Security       $security
    )
    {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {
        $user = $this->security->getUser();

        $this->useCase->execute(
            $user,
            $data->logoId,
            Tier::from($data->tier)
        );

        return null;
    }
}
