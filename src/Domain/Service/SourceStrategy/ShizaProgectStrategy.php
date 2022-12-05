<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Exceptions\FailParseSiteException;
use App\Domain\Interfaces\TorrentStrategyInterface;
use Symfony\Component\DomCrawler\Crawler;

class ShizaProgectStrategy implements TorrentStrategyInterface
{
    private const HTTPS_SHIZA_PROJECT_COM = 'https://shiza-project.com';

    private string $pathUploads;

    private ?string $content = null;

    public function __construct(string $pathUploads)
    {
        $this->pathUploads = $pathUploads;
    }

    private function getContent(string $link): Crawler
    {
        if (null === $this->content) {
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
            throw new FailParseSiteException('Fail get last series', ['message' => $e->getMessage(), 'link' => $link]);
        }
    }

    public function checkNewSeries(string $link): int
    {
        try {
            $crawler = $this->getContent($link);

            return $crawler->filter('.release-online__nav button > span')->last()->html();
        } catch (\Throwable $e) {
            throw new FailParseSiteException('Fail get torrent link', ['message' => $e->getMessage(), 'link' => $link]);
        }
    }
}
