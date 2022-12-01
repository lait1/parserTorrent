<?php
declare(strict_types=1);

namespace App\Domain\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static self SHIZA_PROJECT()
 * @method static self ANILIBRIA()
 * @method static self RUTOR()
 */
final class SourcesEnum extends Enum
{
    private const SHIZA_PROJECT = 'shiza_project';
    private const ANILIBRIA = 'anilibria';
    private const RUTOR = 'rutor';
}
