<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Exceptions\FailParseSiteException;

class AnilibraStrategy extends AbstractSourceStrategy
{
    public function checkNewSeries(): int
    {
        $link = $this->serial->getLink();
        try {
            $crawler = parent::getContent($link);
            $forSearch = $crawler->filter('.torrentcol1')->last()->html();

            preg_match('!Серия\ 1\-(\d+)!', $forSearch, $matches);

            return $matches[1];
        } catch (\Throwable $e) {
            throw new FailParseSiteException("Fail get torrent message {$e->getMessage()}  Link: {$link}");
        }
    }

    protected function getSource(): string
    {
        return 'https://www.anilibria.tv/';
    }

    protected function getTorrentFileLink(): string
    {
        $link = $this->serial->getLink();
        try {
            $crawler = parent::getContent($link);

            return $crawler->filter('.torrent-download-link')->last()->link()->getUri();
        } catch (\Throwable $e) {
            throw new FailParseSiteException("Fail get last series {$e->getMessage()} Link: {$link}");
        }
    }
}
