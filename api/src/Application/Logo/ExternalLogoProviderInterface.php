<?php
namespace App\Application\Logo;

use App\Domain\Logo\Entity\Logo;

interface ExternalLogoProviderInterface
{
    /** @return Logo[] */
    public function fetchLogos(): array;
}
