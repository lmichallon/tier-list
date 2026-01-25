<?php

namespace App\Infrastructure\Command;

use App\Application\Logo\ImportLogosService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'app:import-logos')]
final class ImportLogosCommand extends Command
{
    public function __construct(
        private ImportLogosService $service
    ) {
        parent::__construct();
    }

    protected function execute($input, $output): int
    {
        $this->service->import();
        return Command::SUCCESS;
    }
}
