<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Exceptions\FailParseSiteException;
use App\Domain\Interfaces\TorrentStrategyInterface;
use Symfony\Component\DomCrawler\Crawler;

class AnilibraStrategy implements TorrentStrategyInterface
{
    private const HTTPS_WWW_ANILIBRIA_TV = 'https://www.anilibria.tv/';

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

        return new Crawler($this->content, self::HTTPS_WWW_ANILIBRIA_TV);
    }

    public function getTorrentFileLink(string $link): string
    {
        try {
            $crawler = $this->getContent($link);

            return $crawler->filter('.torrent-download-link')->last()->link()->getUri();
        } catch (\Throwable $e) {
            throw new FailParseSiteException("Fail get last series {$e->getMessage()} Link: {$link}");
        }
    }

    public function checkNewSeries(string $link): int
    {
        try {
            $crawler = $this->getContent($link);
            $forSearch = $crawler->filter('.torrentcol1')->last()->html();

            preg_match('!Серия\ 1\-(\d+)!', $forSearch, $matches);

            return $matches[1];
        } catch (\Throwable $e) {
            throw new FailParseSiteException("Fail get torrent message {$e->getMessage()}  Link: {$link}");
        }
    }
}
