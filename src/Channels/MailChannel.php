<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Messages\ValueObjects\MailMessage;
use Illuminate\Support\Facades\Mail;

class MailChannel implements NotifiableChannelInterface
{
    public function send(NotifiableData $notifiableData): bool
    {
        $message = $notifiableData->getMessage();
        Mail::to($notifiableData->getReceiver())->send(new MailMessage($message['subject'], $message['message'], $message['view']));

        return true;

    }
}