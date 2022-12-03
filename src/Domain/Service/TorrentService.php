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

    private EventDispatcherInterface $eventDispatcher;

    public LoggerInterface $logger;

    public ApiClient $apiClient;

    private string $pathUploads;

    public function __construct(
        TorrentStrategyDefiner $strategyDefiner,
        SerialService $serialService,
        EventDispatcherInterface $eventDispatcher,
        ApiClient $apiClient,
        LoggerInterface $logger,
        string $pathUploads
    ){
        $this->strategyDefiner = $strategyDefiner;
        $this->serialService = $serialService;
        $this->pathUploads = $pathUploads;
        $this->eventDispatcher = $eventDispatcher;
        $this->apiClient = $apiClient;
        $this->logger = $logger;
    }

    public function findNewSeries(): void
    {
       $serials = $this->serialService->getAllSerials();

       foreach ($serials as $serial){
           try {
               $this->process($serial);
           }catch (\Throwable $e){
               continue;
           }
       }
    }

    private function process(Serials $serials): void
    {
        $tracker = $this->strategyDefiner->defineTorrentStrategy($serials->getTypeSource());

        if ($tracker->checkNewSeries($serials->getLink()) > $serials->getLastSeries()) {
            $url = $tracker->getTorrentFileLink($serials->getLink());
            $this->download($url, $serials->getName());
        }

        $event = new InformEvent("Вышла новая серия сериала: {$serials->getName()}");

        $this->eventDispatcher->dispatch($event, InformEvent::NEW);
    }

    public function download($url, $name): void
    {
        $path = sprintf('%s%s%d.torrent', $this->pathUploads, $name, time());

        $file = file_get_contents($url);
        $insert = file_put_contents($path, $file);
        if (!$insert) {
            throw new \RuntimeException('Failed to write torrent');
        }
    }
}