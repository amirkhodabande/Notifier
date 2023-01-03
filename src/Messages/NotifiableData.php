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

    public function setMessage(NotifiableMessage $message): NotifiableData
    {
        $this->messageData = $message;
        return $this;
    }
}