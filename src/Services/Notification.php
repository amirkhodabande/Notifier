<?php

namespace Amir\Notifier\Services;

use Amir\Notifier\Channels\NotifiableChannelInterface;
use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Models\Notification as NotificationModel;
use Illuminate\Support\Facades\Http;

class Notification
{
    public function send(NotifiableChannelInterface $notifiableChannel, NotifiableData $notifiableData): bool
    {
        try {
            Http::retry($notifiableChannel->getRetryTime(), $notifiableChannel->getSleepTime())->post(
                $notifiableChannel->getUrl(),
                array_merge($notifiableChannel->getReceiver(), $notifiableData->getMessage())
            );

            return true;
        } catch (\Exception $exception) {
            NotificationModel::create([
                'channel' => $notifiableChannel::class,
                'status' => false,
                'provider_url' => $notifiableChannel->getUrl(),
                'receiver' => array_values($notifiableChannel->getReceiver())[0],
                'message' => $notifiableData->getMessage()
            ]);

            return false;
        }
    }
}