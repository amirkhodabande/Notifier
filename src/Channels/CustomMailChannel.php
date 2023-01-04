<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class CustomMailChannel implements NotifiableChannelInterface
{
    /**
     * @throws ConnectionException|RequestException
     */
    public function send(NotifiableData $notifiableData): bool
    {
        Http::retry(3, 100)->post(
            config('notifier.email.custom-provider.url'),
            array_merge(
                ['email' => $notifiableData->getReceiver()],
                $notifiableData->getMessage()
            )
        );

        return true;
    }
}