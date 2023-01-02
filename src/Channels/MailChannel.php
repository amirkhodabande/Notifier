<?php

namespace Amir\Notifier\Channels;

class MailChannel implements NotifiableChannelInterface
{
    private string $receiver;

    public function getUrl(): string
    {
        return config('notifier.mail-provider.url');
    }

    public function getRetryTime(): int
    {
       return config('notifier.mail-provider.retry-time');
    }

    public function getSleepTime(): int
    {
        return config('notifier.mail-provider.sleep-time');
    }

    public function getReceiver(): array
    {
         return  ['email' => $this->receiver];
    }

    public function setReceiver(string $receiver): NotifiableChannelInterface
    {
        $this->receiver = $receiver;

        return $this;
    }
}