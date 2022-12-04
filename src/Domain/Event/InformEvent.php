<?php


namespace App\Domain\Event;


use Symfony\Contracts\EventDispatcher\Event;

class InformEvent extends Event
{
    public const NAME = 'monitoring.inform';

    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}