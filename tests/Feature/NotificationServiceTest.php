<?php

namespace Amir\Notifier\Tests\Feature;

use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Messages\ValueObjects\MailMessage;
use Amir\Notifier\Messages\ValueObjects\SMSMessage;
use Amir\Notifier\Services\Notification;
use Amir\Notifier\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_send_email()
    {
        $mailChannel = resolve(MailChannel::class)->setReceiver('test@mail.com');
        $message = resolve(NotifiableData::class)->setMessage(
            new MailMessage('test subject', 'test message')
        );

        Http::shouldReceive('retry')
            ->once()
            ->with($mailChannel->getRetryTime(), $mailChannel->getSleepTime())
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                $mailChannel->getUrl(),
                array_merge($mailChannel->getReceiver(), $message->getMessage())
            )
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = resolve(Notification::class)->send($mailChannel, $message);

        $this->assertTrue($result);
    }

    /** @test */
    public function user_can_send_sms()
    {
        $smsChannel = resolve(SMSChannel::class)->setReceiver('09331234567');
        $message = resolve(NotifiableData::class)->setMessage(
            new SMSMessage('test message')
        );

        Http::shouldReceive('retry')
            ->once()
            ->with($smsChannel->getRetryTime(), $smsChannel->getSleepTime())
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                $smsChannel->getUrl(),
                array_merge($smsChannel->getReceiver(), $message->getMessage())
            )
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = resolve(Notification::class)->send($smsChannel, $message);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_will_save_mail_failed_requests()
    {
        $mailChannel = resolve(MailChannel::class)->setReceiver('test@mail.com');
        $message = resolve(NotifiableData::class)->setMessage(
            new MailMessage('test subject', 'test message')
        );

        Http::shouldReceive('retry')
            ->once()
            ->with($mailChannel->getRetryTime(), $mailChannel->getSleepTime())
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                $mailChannel->getUrl(),
                array_merge($mailChannel->getReceiver(), $message->getMessage())
            )
            ->andThrow(new Exception('Request exception'));

        $result = resolve(Notification::class)->send($mailChannel, $message);

        $this->assertFalse($result);
        $this->assertDatabaseHas('notifications', [
            'channel' => $mailChannel::class,
            'status' => 0,
            'provider_url' => $mailChannel->getUrl(),
            'receiver' => $mailChannel->getReceiver(),
            'message' => json_encode($message->getMessage())
        ]);
    }

    /** @test */
    public function it_will_save_sms_failed_requests()
    {
        $smsChannel = resolve(SMSChannel::class)->setReceiver('09331234567');
        $message = resolve(NotifiableData::class)->setMessage(
            new SMSMessage('test message')
        );

        Http::shouldReceive('retry')
            ->once()
            ->with($smsChannel->getRetryTime(), $smsChannel->getSleepTime())
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                $smsChannel->getUrl(),
                array_merge($smsChannel->getReceiver(), $message->getMessage())
            )
            ->andThrow(new Exception('Request exception'));

        $result = resolve(Notification::class)->send($smsChannel, $message);

        $this->assertFalse($result);
        $this->assertDatabaseHas('notifications', [
            'channel' => $smsChannel::class,
            'status' => 0,
            'provider_url' => $smsChannel->getUrl(),
            'receiver' => $smsChannel->getReceiver(),
            'message' => json_encode($message->getMessage())
        ]);
    }
}