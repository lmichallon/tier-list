<?php

namespace App\Infrastructure\Pdf;

use App\Application\Pdf\PdfDocument;
use App\Application\Pdf\PdfGeneratorInterface;
use App\Domain\TierList\Exception\PdfGenerationFailedException;
use App\Domain\TierList\ValueObject\LogoSnapshot;
use App\Domain\TierList\ValueObject\TierListSnapshot;
use App\Domain\TierList\ValueObject\TierSnapshot;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

final class DompdfGenerator implements PdfGeneratorInterface
{
    public function __construct(
        private Environment $twig
    ) {}

    public function generateTierList(TierListSnapshot $snapshot): PdfDocument
    {
        try {
            $resolvedSnapshot = $this->resolveSnapshotImages($snapshot);
            $html = $this->twig->render('pdf/tierlist.html.twig', [
                'snapshot' => $resolvedSnapshot,
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->render();

            $content = $dompdf->output();
        } catch (\Throwable $exception) {
            throw new PdfGenerationFailedException('Failed to generate PDF.', 0, $exception);
        }

        $filename = sprintf('tierlist-%s.pdf', (new \DateTimeImmutable())->format('Ymd-His'));

        return new PdfDocument($content, $filename);
    }

    private function resolveSnapshotImages(TierListSnapshot $snapshot): TierListSnapshot
    {
        $resolvedTiers = [];

        foreach ($snapshot->tiers as $tier) {
            if (!$tier instanceof TierSnapshot) {
                continue;
            }

            $resolvedLogos = [];
            foreach ($tier->logos as $logo) {
                if (!$logo instanceof LogoSnapshot) {
                    continue;
                }

                $resolvedLogos[] = new LogoSnapshot(
                    $logo->id,
                    $logo->name,
                    $this->resolveImageUrl($logo->imageUrl)
                );
            }

            $resolvedTiers[] = new TierSnapshot(
                $tier->key,
                $tier->label,
                $tier->letter,
                $tier->color,
                $resolvedLogos
            );
        }

        return new TierListSnapshot($snapshot->title, $snapshot->playerDate, $resolvedTiers);
    }

    private function resolveImageUrl(string $url): string
    {
        if ($url === '' || str_starts_with($url, 'data:')) {
            return $url;
        }

        $data = $this->fetchRemoteImage($url);
        if ($data === null) {
            return $url;
        }

        $mimeType = $this->detectMimeType($data, $url);

        return sprintf('data:%s;base64,%s', $mimeType, base64_encode($data));
    }

    private function fetchRemoteImage(string $url): ?string
    {
        if (!str_starts_with($url, 'http')) {
            return null;
        }

        if (function_exists('curl_init')) {
            $handle = curl_init($url);
            if ($handle === false) {
                return null;
            }

            curl_setopt_array($handle, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_USERAGENT => 'tierlist-pdf/1.0',
            ]);

            $data = curl_exec($handle);
            $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);

            if ($data === false || $status >= 400) {
                return null;
            }

            return $data !== '' ? $data : null;
        }

        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'follow_location' => 1,
                'user_agent' => 'tierlist-pdf/1.0',
            ],
        ]);

        $data = @file_get_contents($url, false, $context);

        return $data !== false && $data !== '' ? $data : null;
    }

    private function detectMimeType(string $data, string $url): string
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mime = finfo_buffer($finfo, $data);
                finfo_close($finfo);
                if (is_string($mime) && $mime !== '') {
                    return $mime;
                }
            }
        }

        $path = parse_url($url, PHP_URL_PATH);
        $extension = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : '';

        return match ($extension) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'image/jpeg',
        };
    }
}
