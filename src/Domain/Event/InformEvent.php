<?php


namespace App\Domain\Event;


use Symfony\Contracts\EventDispatcher\Event;

class InformEvent extends Event
{
    public const NEW = 'monitoring.new.series';

    public const FINISH = 'monitoring.inform.finish';

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