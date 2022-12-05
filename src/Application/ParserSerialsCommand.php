<?php

namespace App\Application;

use App\Domain\Service\TorrentService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserSerialsCommand extends Command
{
    private TorrentService $torrentService;

    public function __construct(TorrentService $torrentService)
    {
        parent::__construct('parse:serials');
        $this->torrentService = $torrentService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start finding new series');

        $this->torrentService->findNewSeries();

        $output->writeln('Finish finding new series');

        return Command::SUCCESS;
    }
}
