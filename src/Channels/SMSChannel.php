<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Services\Notification;
use Illuminate\Support\Facades\Http;

class SMSChannel implements NotifiableChannelInterface
{
    private string $receiver;

    public function __construct(private readonly Notification $notificationService)
    {
    }

    public function getUrl(): string
    {
        return config('notifier.sms-provider.url');
    }

    public function getReceiver(): array
    {
        return ['mobile' => $this->receiver];
    }

    public function setReceiver(string $receiver): NotifiableChannelInterface
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function send(NotifiableData $notifiableData): bool
    {
        try {
            Http::retry(
                config('notifier.sms-provider.retry-time'),
                config('notifier.sms-provider.sleep-time')
            )->post(
                $this->getUrl(),
                array_merge($this->getReceiver(), $notifiableData->getMessage())
            );

            return true;
        } catch (\Exception $exception) {
            $this->notificationService->saveFailedNotification($this, $notifiableData);

            return false;
        }
    }
}