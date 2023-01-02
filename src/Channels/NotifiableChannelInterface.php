<?php

namespace Amir\Notifier\Channels;

interface NotifiableChannelInterface
{
    public function getUrl(): string;

    public function getRetryTime(): int;

    public function getSleepTime(): int;

    public function getReceiver(): array;

    public function setReceiver(string $receiver): self;
}