<?php

namespace App\Domain\Interfaces;

interface TorrentStrategyInterface
{
    public function getTorrentFileLink(string $link): string;

    public function checkNewSeries(string $link): int;
}
