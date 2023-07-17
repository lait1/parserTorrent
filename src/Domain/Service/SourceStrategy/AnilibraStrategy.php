<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Exceptions\FailParseSiteException;

class AnilibraStrategy extends AbstractSourceStrategy
{
    private string $order;

    public function checkNewSeries(): int
    {
        $link = $this->serial->getLink();
        try {
            $crawler = parent::getContent($link);
            $lastSearch = $crawler->filter('.torrentcol1')->last()->html();
            $firstSearch = $crawler->filter('.torrentcol1')->first()->html();

            preg_match('!Серия\ (\d+)\-(\d+)!', $lastSearch, $matchesLastSearch);
            preg_match('!Серия\ (\d+)\-(\d+)!', $firstSearch, $matchesFirstSearch);

            $firstSearchSeries = $matchesFirstSearch[2] ?? 1;
            $lastSearchSeries = $matchesLastSearch[2] ?? 1;

            if ($lastSearchSeries > $firstSearchSeries){
                $this->order = 'last';
                $result = $lastSearchSeries;
            }else{
                $this->order = 'first';
                $result = $firstSearchSeries ;
            }

            return $result;
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

            if ($this->order === 'last') {
                return $crawler->filter('.torrent-download-link')->last()->link()->getUri();
            }

            return $crawler->filter('.torrent-download-link')->first()->link()->getUri();

        } catch (\Throwable $e) {
            throw new FailParseSiteException("Fail get last series {$e->getMessage()} Link: {$link}");
        }
    }
}
