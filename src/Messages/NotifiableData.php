<?php

namespace Amir\Notifier\Messages;

use Amir\Notifier\Messages\ValueObjects\NotifiableMessage;

class NotifiableData
{
    private NotifiableMessage $messageData;

    public function getMessage(): array
    {
        return $this->messageData->getMessage();
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function setMessage(NotifiableMessage $message): NotifiableData
    {
        $this->messageData = $message;
        return $this;
    }

    public function setReceiver(string $receiver): NotifiableData
    {
        $this->receiver = $receiver;

        return $this;
    }
}