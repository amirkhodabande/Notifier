<?php

namespace Amir\Notifier\Services;

use Amir\Notifier\Channels\NotifiableChannelInterface;
use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Models\Notification as NotificationModel;

class Notification
{
    public function send(NotifiableChannelInterface $notifiableChannel, NotifiableData $notifiableData): bool
    {
        return $notifiableChannel->send($notifiableData);
    }

    public function saveFailedNotification(NotifiableChannelInterface $notifiableChannel, NotifiableData $notifiableData): void
    {
        NotificationModel::create([
            'channel' => $notifiableChannel::class,
            'status' => false,
            'provider_url' => $notifiableChannel->getUrl(),
            'receiver' => $notifiableData->getReceiver(),
            'message' => $notifiableData->getMessage()
        ]);
    }
}