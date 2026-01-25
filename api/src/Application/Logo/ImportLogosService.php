<?php
namespace App\Application\Logo;

use App\Domain\Logo\Repository\LogoRepositoryInterface;

final class ImportLogosService
{
    public function __construct(
        private ExternalLogoProviderInterface $logoProvider,
        private LogoRepositoryInterface $logoRepository
    ) {}

    public function import(): void
    {
        $logos = $this->logoProvider->fetchLogos();

        foreach ($logos as $logo) {
            if (!$this->logoRepository->existsByCompany($logo->getCompany())) {
                $this->logoRepository->save($logo);
            }
        }
    }
}
