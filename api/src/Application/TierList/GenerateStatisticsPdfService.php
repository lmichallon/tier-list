<?php

namespace App\Application\TierList;

use App\Application\Pdf\PdfDocument;
use App\Application\Pdf\PdfGeneratorInterface;
use App\Domain\TierList\Port\PdfStorageInterface;
use App\Domain\TierList\Repository\TierListRepositoryInterface;

final class GenerateStatisticsPdfService
{
    public function __construct(
        private PdfGeneratorInterface $pdfGenerator,
        private TierListRepositoryInterface $tierListRepository,
        private PdfStorageInterface $pdfStorage
    ) {}

    public function generate(): PdfDocument
    {
        // Récupérer tous les items de toutes les tier lists
        $allItems = $this->tierListRepository->findAllItems();

        // Calculer les statistiques par logo
        $statistics = [];
        foreach ($allItems as $item) {
            $logoId = $item->logo()->getId();
            $logoName = $item->logo()->getCompany();
            $logoImageUrl = $item->logo()->getImageURL();
            $tierValue = $item->tier()->value;

            if (!isset($statistics[$logoId])) {
                $statistics[$logoId] = [
                    'name' => $logoName,
                    'imageUrl' => $logoImageUrl,
                    'S' => 0,
                    'A' => 0,
                    'B' => 0,
                    'C' => 0,
                    'D' => 0,
                ];
            }

            // Incrémenter le compteur pour le tier correspondant
            // Ignorer les items 'unranked' car ils ne sont pas dans les statistiques
            if ($tierValue !== 'unranked' && isset($statistics[$logoId][$tierValue])) {
                $statistics[$logoId][$tierValue]++;
            }
        }

        // Ajouter le total pour chaque logo et trier par total décroissant
        foreach ($statistics as $logoId => &$stat) {
            $stat['total'] = $stat['S'] + $stat['A'] + $stat['B'] + $stat['C'] + $stat['D'];
        }

        // Trier par total décroissant
        usort($statistics, fn($a, $b) => $b['total'] <=> $a['total']);

        $pdfDocument = $this->pdfGenerator->generateStatistics($statistics);

        // Stocker le PDF dans Minio
        $path = 'pdfs/' . $pdfDocument->filename;
        $this->pdfStorage->store($path, $pdfDocument->content);

        // Récupérer l'URL du PDF
        $url = $this->pdfStorage->getUrl($path);

        return new PdfDocument($pdfDocument->content, $pdfDocument->filename, $url);
    }
}
