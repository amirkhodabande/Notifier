<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;use Illuminate\Support\Facades\Http;

class SMSChannel implements NotifiableChannelInterface
{
    /**
     * @throws ConnectionException|RequestException
     */
    public function send(NotifiableData $notifiableData): bool
    {
        Http::retry(3, 100)->post(
            config('notifier.sms.test-provider.url'),
            array_merge(
                ['mobile' => $notifiableData->getReceiver()],
                $notifiableData->getMessage()
            )
        );

        return true;
    }
}