<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Exceptions\FailParseSiteException;
use GuzzleHttp\RequestOptions;

class RutorStrategy extends AbstractSourceStrategy
{
    protected array $options;

    public function __construct(string $pathUploads)
    {
        parent::__construct($pathUploads);

        $this->options = [
            RequestOptions::PROXY => 'http://185.221.160.176:80'
        ];
    }

    public function checkNewSeries(): int
    {
        $link = $this->serial->getLink();

        try {
            $crawler = $this->getContent($link);

            $search = $crawler->filter('.header .button')->text();

            return (int) preg_replace("/[^,.0-9]/", '', $search);
        } catch (\Throwable $e) {
            throw new FailParseSiteException('Fail get torrent link', ['message' => $e->getMessage(), 'link' => $link]);
        }
    }

    protected function getSource(): string
    {
        return 'http://rutor.is/';
    }

    protected function getTorrentFileLink(): string
    {
        $link = $this->serial->getLink();
        try {
            $crawler = $this->getContent($link);

            return $crawler->filter('#download > a')->eq(1)->link()->getUri();
        } catch (\Throwable $e) {
            throw new FailParseSiteException('Fail get last series', ['message' => $e->getMessage(), 'link' => $link]);
        }
    }
}
