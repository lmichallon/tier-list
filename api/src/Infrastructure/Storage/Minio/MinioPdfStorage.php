<?php

namespace App\Infrastructure\Storage\Minio;

use App\Domain\TierList\Port\PdfStorageInterface;
use League\Flysystem\FilesystemOperator;

final class MinioPdfStorage implements PdfStorageInterface
{
    public function __construct(
        private FilesystemOperator $minioStorage,
        private string $bucket,
        private string $publicEndpoint
    ) {}

    public function store(string $path, string $content): void
    {
        $this->minioStorage->write(
            $path,
            $content,
            ['ContentType' => 'application/pdf']
        );
    }

    public function getUrl(string $path): string
    {
        // Générer une URL publique simple (sans pré-signature)
        // Le bucket est configuré pour l'accès public en lecture
        return sprintf(
            '%s/%s/%s',
            rtrim($this->publicEndpoint, '/'),
            $this->bucket,
            $path
        );
    }
}
