<?php

declare(strict_types=1);

namespace App\Domain\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static self ANILIBRIA()
 * @method static self RUTOR()
 */
final class SourcesEnum extends Enum
{
    private const ANILIBRIA = 'anilibria';
    private const RUTOR = 'rutor';
}
