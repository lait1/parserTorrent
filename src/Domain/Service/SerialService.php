<?php

namespace App\Domain\Service;

use App\Domain\DTO\SeriesDTO;
use App\Domain\Entity\Serials;
use App\Domain\Event\InformEvent;
use App\Infrastructure\Repository\SerialsRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SerialService
{
    private SerialsRepository $serialsRepository;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        SerialsRepository $serialsRepository,
        EventDispatcherInterface $eventDispatcher
    ){
        $this->serialsRepository = $serialsRepository;
        $this->eventDispatcher = $eventDispatcher;
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

    public function updateSerial(Serials $serial, int $numberSeries): void
    {
        $serial->setLastSeries($numberSeries);
        $this->serialsRepository->save($serial);

        $event = new InformEvent("Вышла новая {$numberSeries} серия сериала: {$serial->getName()}");
        $this->eventDispatcher->dispatch($event, InformEvent::NAME);

    }
}