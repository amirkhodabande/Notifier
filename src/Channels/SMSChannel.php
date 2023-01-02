<?php

namespace Amir\Notifier\Channels;

class SMSChannel implements NotifiableChannelInterface
{
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
}