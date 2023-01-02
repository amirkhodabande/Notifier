<?php

namespace Amir\Notifier\Channels;

class SMSChannel implements NotifiableChannelInterface
{
    private string $receiver;

    public function getUrl(): string
    {
        return config('notifier.sms-provider.url');
    }

    public function getRetryTime(): int
    {
       return config('notifier.sms-provider.retry-time');
    }

    public function getSleepTime(): int
    {
        return config('notifier.sms-provider.sleep-time');
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
}