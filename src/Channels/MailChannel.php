<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Messages\ValueObjects\MailMessage;
use Amir\Notifier\Services\Notification;
use Illuminate\Support\Facades\Mail;

class MailChannel implements NotifiableChannelInterface
{
    public function __construct(private readonly Notification $notificationService)
    {
    }

    public function send(NotifiableData $notifiableData): bool
    {
        try {
            $message = $notifiableData->getMessage();
            Mail::to($notifiableData->getReceiver())->send(new MailMessage($message['subject'], $message['message'], $message['view']));

            return true;
        } catch (\Exception $exception) {
            $this->notificationService->saveFailedNotification($this, $notifiableData);

            return false;
        }
    }
}