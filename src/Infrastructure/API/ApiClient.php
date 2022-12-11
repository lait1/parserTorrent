<?php

namespace App\Infrastructure\API;

use TelegramBot\Api\BotApi;

class ApiClient
{
    private BotApi $telegramBot;

    private array $messageRecipients;

    public function __construct(
        string $telegramToken,
        array $messageRecipients
    ) {
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
