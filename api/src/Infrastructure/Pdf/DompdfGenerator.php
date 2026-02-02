<?php

namespace App\Infrastructure\Pdf;

use App\Application\Pdf\PdfDocument;
use App\Application\Pdf\PdfGeneratorInterface;
use App\Domain\TierList\Exception\PdfGenerationFailedException;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;

final class DompdfGenerator implements PdfGeneratorInterface
{
    public function __construct(
        private Environment $twig,
        #[Autowire('%env(LOGO_DEV_API_KEY)%')]
        private string $logoDevApiKey
    ) {}

    public function generateStatistics(array $statistics): PdfDocument
    {
        try {
            $resolvedStats = [];
            foreach ($statistics as $stat) {
                $resolvedStats[] = [
                    'name' => $stat['name'],
                    'imageUrl' => $this->resolveImageUrl($stat['imageUrl'] ?? ''),
                    'S' => $stat['S'],
                    'A' => $stat['A'],
                    'B' => $stat['B'],
                    'C' => $stat['C'],
                    'D' => $stat['D'],
                ];
            }

            $html = $this->twig->render('pdf/statistics.html.twig', [
                'statistics' => $resolvedStats,
                'date' => (new \DateTimeImmutable())->format('d/m/Y à H:i'),
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->render();

            $content = $dompdf->output();
        } catch (\Throwable $exception) {
            throw new PdfGenerationFailedException('Failed to generate statistics PDF.', 0, $exception);
        }

        $filename = sprintf('statistics-%s.pdf', (new \DateTimeImmutable())->format('Ymd-His'));

        return new PdfDocument($content, $filename);
    }

    private function resolveImageUrl(string $url): string
    {
        if ($url === '' || str_starts_with($url, 'data:')) {
            return $url;
        }

        $data = $this->fetchRemoteImage($url);
        if (!$data) {
            return '';
        }

        $mimeType = $this->detectMimeType($data);

        return sprintf('data:%s;base64,%s', $mimeType, base64_encode($data));
    }

    private function fetchRemoteImage(string $url): ?string
    {
        if (str_contains($url, 'img.logo.dev') && !str_contains($url, 'token=')) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'token=' . $this->logoDevApiKey;
        }

        $handle = curl_init($url);
        if (!$handle) {
            return null;
        }

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $data = curl_exec($handle);
        $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return ($data && $status < 400) ? $data : null;
    }

    private function detectMimeType(string $data): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, $data);
        finfo_close($finfo);

        return $mime ?: 'image/jpeg';
    }
}
