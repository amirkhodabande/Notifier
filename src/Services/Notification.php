<?php

namespace Amir\Notifier\Services;

use Amir\Notifier\Channels\NotifiableChannelInterface;
use Amir\Notifier\Messages\NotifiableMessage;
use Illuminate\Support\Facades\Http;

class Notification
{
    public function send(NotifiableChannelInterface $notifiableChannel, NotifiableMessage $notifiableMessage): bool
    {
        try {
            Http::retry($notifiableChannel->getRetryTime(), $notifiableChannel->getSleepTime())->post(
                $notifiableChannel->getUrl(),
                $notifiableMessage->getMessage()
            );
            return true;
        } catch (\Exception $exception) {
//            TODO: error handling
            throw new $exception;
        }
    }
}