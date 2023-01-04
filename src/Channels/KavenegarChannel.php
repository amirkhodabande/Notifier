<?php

namespace Amir\Notifier\Channels;

use Amir\Notifier\Messages\NotifiableData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class KavenegarChannel implements NotifiableChannelInterface
{
    /**
     * @throws ConnectionException|RequestException
     */
    public function send(NotifiableData $notifiableData): bool
    {
        Http::retry(3, 100)->post(
            config('notifier.sms.kavenegar.url').'/'.config('notifier.sms.kavenegar.api-key'),
            array_merge(
                [
                    'sender' => config('notifier.sms.kavenegar.sender'),
                    'receptor' => $notifiableData->getReceiver()
                ],
                $notifiableData->getMessage()
            )
        );

        return true;
    }
}