<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Services\Notification;
use Illuminate\Support\Facades\Http;

class CustomMailChannel implements NotifiableChannelInterface
{
    public function __construct(private readonly Notification $notificationService)
    {
    }

    public function send(NotifiableData $notifiableData): bool
    {
        try {
            Http::retry(3, 100)->post(
                config('notifier.email.custom-provider.url'),
                array_merge(
                    ['email' => $notifiableData->getReceiver()],
                    $notifiableData->getMessage()
                )
            );

            return true;
        } catch (\Exception $exception) {
            $this->notificationService->saveFailedNotification($this, $notifiableData);

            return false;
        }
    }
}