<?php

namespace App\Interface\Api\Processor\Pdf;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\TierList\GenerateStatisticsPdfService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class GenerateTierListPdfProcessor implements ProcessorInterface
{
    public function __construct(
        private GenerateStatisticsPdfService $generateStatisticsPdfService,
        private RequestStack $requestStack
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Response {
        $pdfDocument = $this->generateStatisticsPdfService->generate();

        return new JsonResponse([
            'url' => $pdfDocument->url,
            'filename' => $pdfDocument->filename,
        ]);
    }
}
