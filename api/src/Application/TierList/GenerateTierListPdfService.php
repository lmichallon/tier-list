<?php

namespace App\Application\TierList;

use App\Application\Pdf\PdfDocument;
use App\Application\Pdf\PdfGeneratorInterface;
use App\Domain\TierList\ValueObject\TierListSnapshot;

final class GenerateTierListPdfService
{
    public function __construct(
        private PdfGeneratorInterface $pdfGenerator
    ) {}

    public function generate(TierListSnapshot $snapshot): PdfDocument
    {
        return $this->pdfGenerator->generateTierList($snapshot);
    }
}
