<?php


namespace App\Domain\Service\SourceStrategy;


use App\Domain\Interfaces\TorrentStrategyInterface;
use App\Infrastructure\API\ApiClient;
use Doctrine\DBAL\Exception;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Path;

class ShizaProgectStrategy implements TorrentStrategyInterface
{
    const HTTPS_SHIZA_PROJECT_COM = 'https://shiza-project.com';

    private string $pathUploads;

    private ?string $content = null;

    public function __construct(string $pathUploads)
    {
        $this->pathUploads = $pathUploads;
    }

    private function getContent(string $link): Crawler
    {
        if ($this->content === null){
            $this->content = file_get_contents($link);
        }
        return new Crawler($this->content, self::HTTPS_SHIZA_PROJECT_COM);
    }

    public function getTorrentFileLink(string $link): string
    {

        try {
            $crawler = $this->getContent($link);

            return $crawler->filter('.button-success')->link()->getUri();
        } catch (\Throwable $e) {
            dd($e);
        }
    }
    public function checkNewSeries(string $link): int
    {
        try {
            $crawler = $this->getContent($link);

            return $crawler->filter('.release-online__nav button > span')->last()->html();
        } catch (\Throwable $e) {
            dd($e);
        }
    }

}
