<?php

namespace Amir\Notifier\Services;

use Amir\Notifier\Channels\NotifiableChannelInterface;
use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Models\Notification as NotificationModel;

class Notification
{
    public function send(NotifiableChannelInterface $notifiableChannel, NotifiableData $notifiableData): bool
    {
        try {
            return $notifiableChannel->send($notifiableData);
        } catch (\Exception $exception) {
            $this->saveFailedNotification($notifiableChannel, $notifiableData);

            return false;
        }
    }

    private function saveFailedNotification(NotifiableChannelInterface $notifiableChannel, NotifiableData $notifiableData): void
    {
        NotificationModel::create([
            'channel' => $notifiableChannel::class,
            'status' => false,
            'receiver' => $notifiableData->getReceiver(),
            'message' => $notifiableData->getMessage()
        ]);
    }
}