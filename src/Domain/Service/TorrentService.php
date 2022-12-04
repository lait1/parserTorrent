<?php


namespace App\Domain\Service;


use App\Domain\Entity\Serials;
use App\Domain\Event\InformEvent;
use App\Domain\Service\SourceStrategy\TorrentStrategyDefiner;
use App\Infrastructure\API\ApiClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TorrentService
{
    private TorrentStrategyDefiner $strategyDefiner;

    private SerialService $serialService;

    public LoggerInterface $logger;

    public ApiClient $apiClient;

    private string $pathUploads;

    public function __construct(
        TorrentStrategyDefiner $strategyDefiner,
        SerialService $serialService,
        ApiClient $apiClient,
        LoggerInterface $logger,
        string $pathUploads
    ){
        $this->strategyDefiner = $strategyDefiner;
        $this->serialService = $serialService;
        $this->pathUploads = $pathUploads;
        $this->apiClient = $apiClient;
        $this->logger = $logger;
    }

    public function findNewSeries(bool $disableParsing = false): void
    {
       $serials = $this->serialService->getAllSerials();

        foreach ($serials as $serial) {
            try {
                $this->process($serial, $disableParsing);
            } catch (\Throwable $e) {
                $this->logger->error('Fail process check new series',
                    [
                        'message' => $e->getMessage()
                    ]);
                continue;
            }
       }
    }

    private function process(Serials $serial, bool $disableParsing): void
    {
        $this->logger->info("Start parser", ['serial' => $serial->getName()]);

        $tracker = $this->strategyDefiner->defineTorrentStrategy($serial->getTypeSource());
        $numberSeries = $tracker->checkNewSeries($serial->getLink());

        if (!$disableParsing && $numberSeries > $serial->getLastSeries()) {
            $this->logger->info("Found a new series {$numberSeries}}", ['serial' => $serial->getName()]);

            $url = $tracker->getTorrentFileLink($serial->getLink());
            $this->download($url, $serial->getName());

            $this->logger->info("torrent files have been downloaded", ['serial' => $serial->getName()]);

            $this->serialService->updateSerial($serial, $numberSeries);
        }

    }

    private function download(string $url, string $name): void
    {
        $fileName = str_replace(' ', '_', $name);
        $path = sprintf('%s%s_%d.torrent', $this->pathUploads, $fileName, time());

        $file = file_get_contents($url);
        $insert = file_put_contents($path, $file);
        if (!$insert) {
            throw new \RuntimeException('Failed to write torrent');
        }
    }
}