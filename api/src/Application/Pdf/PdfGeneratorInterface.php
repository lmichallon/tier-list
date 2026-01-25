<?php

namespace App\Application\Pdf;

use App\Domain\TierList\ValueObject\TierListSnapshot;

interface PdfGeneratorInterface
{
    public function generateTierList(TierListSnapshot $snapshot): PdfDocument;
}
