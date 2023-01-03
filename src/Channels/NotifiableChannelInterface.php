<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;

interface NotifiableChannelInterface
{
    public function getUrl(): string;

    public function getReceiver(): array;

    public function setReceiver(string $receiver): self;

    public function send(NotifiableData $notifiableData): bool;
}