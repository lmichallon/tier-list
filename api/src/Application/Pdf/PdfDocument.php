<?php

namespace App\Application\Pdf;

final class PdfDocument
{
    public function __construct(
        public readonly string $content,
        public readonly string $filename,
        public readonly string $mimeType = 'application/pdf'
    ) {}
}
