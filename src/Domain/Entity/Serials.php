<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\SerialsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SerialsRepository::class)
 */
class Serials
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $link;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $typeSource;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private DateTime $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=false) */
    private DateTime $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDeleted = false;

    public function __construct(
        string $name,
        string $link,
        string $typeSource,
        string $description
    ){
        $this->name = $name;
        $this->link = $link;
        $this->typeSource = $typeSource;
        $this->description = $description;
        $this->createdAt = new \DateTime();
    }


}
