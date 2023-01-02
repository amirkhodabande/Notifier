<?php

namespace Amir\Notifier\Channels;

class MailChannel implements NotifiableChannelInterface
{
    public function getUrl(): string
    {
        return 'https://www.mail.com';
    }

    public function getRetryTime(): int
    {
       return 3;
    }

    public function getSleepTime(): int
    {
        return 100;
    }
}