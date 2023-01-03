<?php

namespace Amir\Notifier\Messages\ValueObjects;

class SMSMessage implements NotifiableMessage
{
    public function __construct(public readonly string $message)
    {
    }

    public function getMessage(): array
    {
        return [
            'message' => $this->message
        ];
    }
}