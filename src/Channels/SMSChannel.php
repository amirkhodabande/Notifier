<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Services\Notification;
use Illuminate\Support\Facades\Http;

class SMSChannel implements NotifiableChannelInterface
{
    public function __construct(private readonly Notification $notificationService)
    {
    }

    public function send(NotifiableData $notifiableData): bool
    {
        try {
            Http::retry(3, 100)->post(
                config('notifier.sms.test-provider.url'),
                array_merge(
                    ['mobile' => $notifiableData->getReceiver()],
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