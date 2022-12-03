<?php

namespace App\Application;

use App\Domain\Event\InformEvent;
use App\Infrastructure\API\ApiClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Notificator implements EventSubscriberInterface
{
    public ApiClient $apiClient;

    public LoggerInterface $logger;

    public function __construct(ApiClient $apiClient, LoggerInterface $logger)
    {
        $this->apiClient = $apiClient;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InformEvent::NEW => 'onNewSeries',
            InformEvent::FINISH => 'onFinishDownload'
        ];
    }

    public function onNewSeries(InformEvent $event): void
    {
        $this->logger->info('Send message', [
            $event->getMessage()
        ]);
//        try {
            $this->apiClient->sendMessage($event->getMessage());
//        } catch (\Throwable $exception) {
//
//        }
    }

    public function onFinishDownload(InformEvent $event): void
    {
//        try {
        $this->apiClient->sendMessage($event->getMessage());
//        } catch (\Throwable $exception) {
//
//        }
    }
}