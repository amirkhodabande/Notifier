<?php

namespace Amir\Notifier\Channels;

class MailChannel implements NotifiableChannelInterface
{
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
}