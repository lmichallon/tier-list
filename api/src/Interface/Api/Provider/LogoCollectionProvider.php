<?php

namespace App\Interface\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Domain\Logo\Repository\LogoRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class LogoCollectionProvider implements ProviderInterface
{
    public function __construct(
        private LogoRepositoryInterface $logoRepository,
        #[Autowire('%env(LOGO_DEV_API_KEY)%')]
        private string $apiKey
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $logos = $this->logoRepository->findAll();

        foreach ($logos as $logo) {
            yield [
                'id' => $logo->getId(),
                'company' => $logo->getCompany(),
                'imageURL' => sprintf(
                    '%s?token=%s',
                    $logo->getImageURL(),
                    $this->apiKey
                ),
            ];
        }
    }
}
