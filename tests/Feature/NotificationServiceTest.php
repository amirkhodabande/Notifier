<?php

use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Messages\NotifiableMessage;
use Amir\Notifier\Services\Notification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class NotificationServiceTest extends TestCase
{
    /** @test */
    public function user_can_send_email()
    {
        $mailChannel = resolve(MailChannel::class);
        $message = resolve(NotifiableMessage::class)->setMessage([
            'subject' => 'test subject',
            'message' => 'test message'
        ]);

        Http::shouldReceive('retry')
            ->once()
            ->with($mailChannel->getRetryTime(), $mailChannel->getSleepTime())
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with($mailChannel->getUrl(), $message->getMessage())
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = resolve(Notification::class)->send($mailChannel, $message);

        $this->assertTrue($result);
    }

    /** @test */
    public function user_can_send_sms()
    {
        $smsChannel = resolve(SMSChannel::class);
        $message = resolve(NotifiableMessage::class)->setMessage([
            'message' => 'test message'
        ]);

        Http::shouldReceive('retry')
            ->once()
            ->with($smsChannel->getRetryTime(), $smsChannel->getSleepTime())
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with($smsChannel->getUrl(), $message->getMessage())
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = resolve(Notification::class)->send($smsChannel, $message);

        $this->assertTrue($result);
    }
}