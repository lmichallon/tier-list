<?php

namespace App\Application\Service;

use App\Domain\Logo\Entity\Logo;
use App\Domain\Logo\Repository\LogoRepositoryInterface;

use App\Domain\Logo\Exception\LogoAlreadyExistsException;
use App\Domain\Logo\Exception\LogoLimitReachedException;

class LogoApplicationService
{
    public function __construct(
        private LogoRepositoryInterface $logoRepository
    ) {}

    public function addLogo(string $company, string $imageURL): void
    {
        if ($this->logoRepository->existsByCompany($company)) {
            throw new LogoAlreadyExistsException();
        }

        if ($this->logoRepository->count() >= 10) {
            throw new LogoLimitReachedException();
        }

        $logo = new Logo($company, $imageURL);
        $this->logoRepository->save($logo);
    }
}
