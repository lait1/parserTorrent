<?php

namespace App\Domain\Entity;

use App\Domain\Enum\SourcesEnum;

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
     * @ORM\Column(type="integer")
     */
    private string $lastSeries;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private \DateTime $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private \DateTime $updatedAt;

    /**
     * @ORM\Column(type="boolean", options={"unsigned": true, "default": 0})
     */
    private bool $isDeleted = false;

    public function __construct(
        string $name,
        string $link,
        int $lastSeries,
        string $typeSource,
        string $description
    ) {
        $this->name = $name;
        $this->link = $link;
        $this->lastSeries = $lastSeries;
        $this->typeSource = $typeSource;
        $this->description = $description;
        $this->createdAt = new \DateTime('now', new \DateTimeZone('Asia/Tbilisi'));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeSource(): SourcesEnum
    {
        return new SourcesEnum($this->typeSource);
    }

    public function getUpdatedAt(): string
    {
        if (!isset($this->updatedAt)) {
            return 'no parsing yet';
        }

        return $this->updatedAt->format('d-m-Y H:i:s');
    }

    public function deleted(): void
    {
        $this->isDeleted = true;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->format('d-m-Y H:i:s');
    }

    public function getLastSeries(): int
    {
        return $this->lastSeries;
    }

    public function setLastSeries(int $lastSeries): void
    {
        $this->lastSeries = $lastSeries;
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('Asia/Tbilisi'));
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setTypeSource(string $typeSource): void
    {
        $this->typeSource = $typeSource;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
