<?php


namespace App\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SeriesDTO
{
    /** @Assert\NotBlank(message="name not found") */
    public string $name;

    /** @Assert\NotBlank(message="link not found") */
    public string $link;

    /** @Assert\NotBlank(message="type not found") */
    public string $typeSource;

    public ?string $lastSeries;

    public ?string $description;
}