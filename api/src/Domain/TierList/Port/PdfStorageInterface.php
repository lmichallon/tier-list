<?php

namespace App\Domain\TierList\Port;

interface PdfStorageInterface
{
    public function store(string $path, string $content): void;

    public function getUrl(string $path): string;
}
