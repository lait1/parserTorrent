<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Entity\Serials;
use App\Domain\Exceptions\FailParseSiteException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractSourceStrategy
{
    protected Serials $serial;

    private ?string $content = null;

    private string $pathUploads;

    protected array $options = [];

    public function __construct(string $pathUploads)
    {
        $this->pathUploads = $pathUploads;
    }

    abstract public function checkNewSeries(): int;

    public function setSerial(Serials $serial): void
    {
        $this->serial = $serial;
        $this->content = null;
    }

    public function download(): void
    {
        $serialName = $this->serial->getName();
        $fileName = str_replace(' ', '_', $serialName);
        $path = sprintf('%s%s_%d.torrent', $this->pathUploads, $fileName, time());

        $file_path = fopen($path, 'w');

        $client = new Client();
        $response = $client->request('GET', $this->getTorrentFileLink(), $this->options + ['sink' => $file_path]);

        if ($response->getStatusCode() !== 200) {
            throw new FailParseSiteException("Failed to write torrent {$serialName}");
        }
    }

    abstract protected function getSource(): string;

    abstract protected function getTorrentFileLink(): string;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getContent(string $link): Crawler
    {
        if (null === $this->content) {
            $client = new Client();
            $response = $client->request('GET', $link, $this->options);
            $this->content = $response->getBody()->getContents();
        }
        return new Crawler($this->content, $this->getSource());
    }
}
