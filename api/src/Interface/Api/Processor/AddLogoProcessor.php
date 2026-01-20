<?php

namespace App\Interface\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Service\LogoApplicationService;
use App\Interface\Api\Resource\LogoResource;

final class AddLogoProcessor implements ProcessorInterface
{
    public function __construct(
        private LogoApplicationService $logoService
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): void {
        /** @var LogoResource $data */

        $this->logoService->addLogo(
            $data->company,
            $data->imageURL
        );
    }
}
