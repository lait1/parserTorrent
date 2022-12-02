<?php


namespace App\Domain\Service;


use App\Domain\Entity\Serials;
use App\Domain\Service\SourceStrategy\TorrentStrategyDefiner;

class TorrentService
{
    private TorrentStrategyDefiner $strategyDefiner;

    private SerialService $serialService;

    private string $pathUploads;

    public function __construct(
        TorrentStrategyDefiner $strategyDefiner,
        SerialService $serialService,
        string $pathUploads
    ){
        $this->strategyDefiner = $strategyDefiner;
        $this->serialService = $serialService;
        $this->pathUploads = $pathUploads;
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