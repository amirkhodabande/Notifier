<?php

namespace Amir\Notifier\Messages\ValueObjects;

use Exception;

class MailMessage implements NotifiableMessage
{
    public function __construct(public readonly string $subject, public readonly string $message)
    {
        if (strlen($subject) > 250) {
            throw new Exception('Subject cant be greater than 250 characters.');
        }
    }

    public function getMessage(): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message
        ];
    }
}