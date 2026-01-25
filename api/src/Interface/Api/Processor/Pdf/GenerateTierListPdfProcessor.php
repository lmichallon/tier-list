<?php

namespace App\Interface\Api\Processor\Pdf;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\TierList\GenerateTierListPdfService;
use App\Domain\TierList\ValueObject\LogoSnapshot;
use App\Domain\TierList\ValueObject\TierListSnapshot;
use App\Domain\TierList\ValueObject\TierSnapshot;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class GenerateTierListPdfProcessor implements ProcessorInterface
{
    public function __construct(
        private GenerateTierListPdfService $generateTierListPdfService,
        private RequestStack $requestStack
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Response {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            throw new BadRequestHttpException('Missing request.');
        }

        try {
            $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new BadRequestHttpException('Invalid JSON payload.', $exception);
        }

        if (!is_array($payload)) {
            throw new BadRequestHttpException('Invalid payload.');
        }

        $title = trim((string) ($payload['title'] ?? ''));
        $playerDate = trim((string) ($payload['playerDate'] ?? ''));
        $tiersPayload = $payload['tiers'] ?? null;

        if ($title === '' || $playerDate === '' || !is_array($tiersPayload)) {
            throw new BadRequestHttpException('Missing required fields.');
        }

        $tiers = [];
        foreach ($tiersPayload as $tierPayload) {
            if (!is_array($tierPayload)) {
                continue;
            }

            $key = trim((string) ($tierPayload['key'] ?? ''));
            $label = trim((string) ($tierPayload['label'] ?? ''));
            $letter = trim((string) ($tierPayload['letter'] ?? ''));
            $color = trim((string) ($tierPayload['color'] ?? '#e5e7eb'));
            $logosPayload = $tierPayload['logos'] ?? [];

            if ($key === '' || $label === '') {
                continue;
            }

            $logos = [];
            if (is_array($logosPayload)) {
                foreach ($logosPayload as $logoPayload) {
                    if (!is_array($logoPayload)) {
                        continue;
                    }

                    $logoId = trim((string) ($logoPayload['id'] ?? ''));
                    $logoName = trim((string) ($logoPayload['name'] ?? ''));
                    $imageUrl = trim((string) ($logoPayload['imageUrl'] ?? ''));

                    if ($logoId === '' || $logoName === '' || $imageUrl === '') {
                        continue;
                    }

                    $logos[] = new LogoSnapshot($logoId, $logoName, $imageUrl);
                }
            }

            $tiers[] = new TierSnapshot($key, $label, $letter, $color, $logos);
        }

        if ($tiers === []) {
            throw new BadRequestHttpException('No tiers provided.');
        }

        $snapshot = new TierListSnapshot($title, $playerDate, $tiers);
        $pdfDocument = $this->generateTierListPdfService->generate($snapshot);

        $response = new Response(
            $pdfDocument->content,
            Response::HTTP_OK,
            [
                'Content-Type' => $pdfDocument->mimeType,
                'Content-Disposition' => sprintf('attachment; filename="%s"', $pdfDocument->filename),
            ]
        );

        return $response;
    }
}
