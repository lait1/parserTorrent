<?php

namespace App\Domain\Service;

use App\Domain\DTO\SeriesDTO;
use App\Domain\Entity\Serials;
use App\Infrastructure\Repository\SerialsRepository;

class SerialService
{
    private SerialsRepository $serialsRepository;

    public function __construct(SerialsRepository $serialsRepository)
    {
        $this->serialsRepository = $serialsRepository;
    }

    public function getAllSerials(): array
    {
        return $this->serialsRepository->findBy(['isDeleted' => 0]);
    }

    public function findSerial(int $id): ?Serials
    {
        return $this->serialsRepository->findOneBy(['id' => $id]);
    }

    public function removeSerial(int $id): void
    {
        $serial = $this->serialsRepository->findOneBy(['id' => $id]);
        $serial->deleted();
        $this->serialsRepository->save($serial);
    }

    public function createSerial(SeriesDTO $dto): void
    {
        $serial = new Serials(
            $dto->name,
            $dto->link,
            $dto->lastSeries,
            $dto->typeSource,
            $dto->description
        );

        $this->serialsRepository->save($serial);
    }


}