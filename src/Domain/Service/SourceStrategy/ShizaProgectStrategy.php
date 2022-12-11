<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Exceptions\FailParseSiteException;

/** @deprecated */
class ShizaProgectStrategy extends AbstractSourceStrategy
{
    protected function getSource(): string
    {
        return 'https://shiza-project.com/';
    }

    protected function getTorrentFileLink(): string
    {
        $link = $this->serial->getLink();
        try {
            $crawler = $this->getContent($link);

            return $crawler->filter('.button-success')->link()->getUri();
        } catch (\Throwable $e) {
            throw new FailParseSiteException('Fail get last series', ['message' => $e->getMessage(), 'link' => $link]);
        }
    }

    public function checkNewSeries(): int
    {
        $link = $this->serial->getLink();
        try {
            $crawler = $this->getContent($link);

            return $crawler->filter('.release-online__nav button > span')->last()->html();
        } catch (\Throwable $e) {
            throw new FailParseSiteException('Fail get torrent link', ['message' => $e->getMessage(), 'link' => $link]);
        }
    }
}
