<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;

interface NotifiableChannelInterface
{
    public function send(NotifiableData $notifiableData): bool;
}