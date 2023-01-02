<?php

namespace Amir\Notifier\Channels;

interface NotifiableChannelInterface
{
    public function getUrl(): string;

    public function getRetryTime(): int;

    public function getSleepTime(): int;
}