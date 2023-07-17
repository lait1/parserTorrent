<?php

namespace App\Domain\Service\SourceStrategy;

use App\Domain\Enum\SourcesEnum;

class TorrentStrategyDefiner
{
    private AnilibraStrategy $anilibraStrategy;

    private RutorStrategy $rutorStrategy;

    private ShizaProgectStrategy $shizaProgectStrategy;

    public function __construct(
        AnilibraStrategy $anilibraStrategy,
        RutorStrategy $rutorStrategy,
        ShizaProgectStrategy $shizaProgectStrategy
    ) {
        $this->anilibraStrategy = $anilibraStrategy;
        $this->rutorStrategy = $rutorStrategy;
        $this->shizaProgectStrategy = $shizaProgectStrategy;
    }

    public function defineTorrentStrategy(SourcesEnum $sourcesEnum): AbstractSourceStrategy
    {
        switch ($sourcesEnum) {
            case SourcesEnum::ANILIBRIA():
                return $this->anilibraStrategy;
            case SourcesEnum::RUTOR():
                return $this->rutorStrategy;
        }

        throw new \LogicException('Undefined type');
    }
}
