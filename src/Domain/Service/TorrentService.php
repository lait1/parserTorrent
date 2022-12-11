<?php

namespace App\Domain\Service;

use App\Domain\Entity\Serials;
use App\Domain\Event\InformEvent;
use App\Domain\Exceptions\FailParseSiteException;
use App\Domain\Service\SourceStrategy\TorrentStrategyDefiner;
use App\Infrastructure\API\ApiClient;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TorrentService
{
    private TorrentStrategyDefiner $strategyDefiner;

    private SerialService $serialService;

    private LoggerInterface $logger;

    private EventDispatcherInterface $eventDispatcher;


    public function __construct(
        TorrentStrategyDefiner $strategyDefiner,
        SerialService $serialService,
        LoggerInterface $logger,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->strategyDefiner = $strategyDefiner;
        $this->serialService = $serialService;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    public function findNewSeries(): void
    {
        $serials = $this->serialService->getAllSerials();

        foreach ($serials as $serial) {
            try {
                $this->process($serial);
            } catch (FailParseSiteException $e) {
                $this->eventDispatcher->dispatch(new InformEvent($e->getMessage()), InformEvent::NAME);
            } catch (\Throwable $e) {
                $this->logger->error(
                    'Fail process check new series',
                    [
                        'message' => $e->getMessage(),
                    ]
                );
                continue;
            }
        }
    }

    private function process(Serials $serial): void
    {
        $this->logger->info('Start parser', ['serial' => $serial->getName()]);

        $tracker = $this->strategyDefiner->defineTorrentStrategy($serial->getTypeSource());
        $tracker->setSerial($serial);
        $numberSeries = $tracker->checkNewSeries();

        if ($numberSeries > $serial->getLastSeries()) {
            $this->logger->info("Found a new series {$numberSeries}}", ['serial' => $serial->getName()]);

            $tracker->download();

            $this->logger->info('torrent files have been downloaded', ['serial' => $serial->getName()]);

            $this->serialService->updateNumberSerial($serial, $numberSeries);
        }
    }
}
