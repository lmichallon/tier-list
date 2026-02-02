<?php

namespace App\Application\Pdf;

use App\Domain\TierList\ValueObject\TierListSnapshot;

interface PdfGeneratorInterface
{

    /**
     * @param array<array{name: string, imageUrl: string, S: int, A: int, B: int, C: int, D: int, total: int}> $statistics
     */
    public function generateStatistics(array $statistics): PdfDocument;
}
