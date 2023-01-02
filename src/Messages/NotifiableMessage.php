<?php

namespace Amir\Notifier\Messages;

class NotifiableMessage
{
    private array $messageData = [];

    public function getMessage(): array
    {
        return $this->messageData;
    }

    public function setMessage(array $messageData): NotifiableMessage
    {
        $this->messageData = $messageData;
        return $this;
    }
}