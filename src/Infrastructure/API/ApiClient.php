<?php

namespace App\Infrastructure\API;

use GuzzleHttp\ClientInterface;
use TelegramBot\Api\BotApi;

class ApiClient
{
    private ClientInterface $client;

    private BotApi $telegramBot;

    private array $messageRecipients;

    public function __construct(
        ClientInterface $client,
        string $telegramToken,
        array $messageRecipients
    ) {
        $this->client = $client;
        $this->telegramBot = new BotApi($telegramToken);
        $this->messageRecipients = $messageRecipients;
    }

    public function sendMessage(string $message): void
    {
        foreach ($this->messageRecipients as $recipient) {
            $this->telegramBot->sendMessage($recipient, $message);
        }
    }
}
