<?php

namespace Amir\Notifier\Tests\Feature;

use Amir\Notifier\Channels\CustomMailChannel;
use Amir\Notifier\Channels\KavenegarChannel;
use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Messages\ValueObjects\CustomMailMessage;
use Amir\Notifier\Messages\ValueObjects\MailMessage;
use Amir\Notifier\Messages\ValueObjects\SMSMessage;
use Amir\Notifier\Services\Notification;
use Amir\Notifier\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_send_email()
    {
        $mailChannel = resolve(CustomMailChannel::class);
        $message = resolve(NotifiableData::class)->setReceiver('test@mail.com')
            ->setMessage(
                new CustomMailMessage('test subject', 'test message')
            );

        Http::shouldReceive('retry')
            ->once()
            ->with(3, 100)
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                config('notifier.email.custom-provider.url'),
                array_merge(
                    ['email' => $message->getReceiver()],
                    $message->getMessage()
                )
            )
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = resolve(Notification::class)->send($mailChannel, $message);

        $this->assertTrue($result);
    }

    /** @test */
    public function user_can_send_sms()
    {
        $smsChannel = resolve(SMSChannel::class);
        $message = resolve(NotifiableData::class)->setReceiver('09331234567')
            ->setMessage(
                new SMSMessage('test message')
            );

        Http::shouldReceive('retry')
            ->once()
            ->with(3, 100)
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                config('notifier.sms.test-provider.url'),
                array_merge(
                    ['mobile' => $message->getReceiver()],
                    $message->getMessage()
                )
            )
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = resolve(Notification::class)->send($smsChannel, $message);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_will_save_mail_failed_requests()
    {
        $mailChannel = resolve(CustomMailChannel::class);
        $message = resolve(NotifiableData::class)->setReceiver('test@mail.com')
            ->setMessage(
                new CustomMailMessage('test subject', 'test message')
            );

        Http::shouldReceive('retry')
            ->once()
            ->with(3, 100)
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                config('notifier.email.custom-provider.url'),
                array_merge(
                    ['email' => $message->getReceiver()],
                    $message->getMessage()
                )
            )
            ->andThrow(new Exception('Request exception'));

        $result = resolve(Notification::class)->send($mailChannel, $message);

        $this->assertFalse($result);
        $this->assertDatabaseHas('notifications', [
            'channel' => $mailChannel::class,
            'status' => 0,
            'receiver' => $message->getReceiver(),
            'message' => json_encode($message->getMessage())
        ]);
    }

    /** @test */
    public function it_will_save_sms_failed_requests()
    {
        $smsChannel = resolve(SMSChannel::class);
        $message = resolve(NotifiableData::class)->setReceiver('09331234567')
            ->setMessage(
                new SMSMessage('test message')
            );

        Http::shouldReceive('retry')
            ->once()
            ->with(3, 100)
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                config('notifier.sms.test-provider.url'),
                array_merge(
                    ['mobile' => $message->getReceiver()],
                    $message->getMessage()
                )
            )
            ->andThrow(new Exception('Request exception'));

        $result = resolve(Notification::class)->send($smsChannel, $message);

        $this->assertFalse($result);
        $this->assertDatabaseHas('notifications', [
            'channel' => $smsChannel::class,
            'status' => 0,
            'receiver' => $message->getReceiver(),
            'message' => json_encode($message->getMessage())
        ]);
    }

    /** @test */
    public function user_can_send_email_using_the_laravel_mailer()
    {
        Mail::fake();

        $mailChannel = resolve(MailChannel::class);
        $message = resolve(NotifiableData::class)->setReceiver('test@mail.com')
            ->setMessage(
                new MailMessage('test subject', 'test message', 'notification::emails.sample')
            );

        Mail::shouldReceive('to')
            ->once()
            ->with($message->getReceiver())
            ->andReturnSelf();

        Mail::shouldReceive('send')
            ->once();

        $result = resolve(Notification::class)->send($mailChannel, $message);

        $this->assertTrue($result);
    }

    /** @test */
    public function user_can_send_sms_with_kavenegar()
    {
        $smsChannel = resolve(KavenegarChannel::class);
        $message = resolve(NotifiableData::class)->setReceiver('09331234567')
            ->setMessage(
                new SMSMessage('test message')
            );

        Http::shouldReceive('retry')
            ->once()
            ->with(3, 100)
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with(
                config('notifier.sms.kavenegar.url').'/'.config('notifier.sms.kavenegar.api-key'),
                array_merge(
                    [
                        'sender' => config('notifier.sms.kavenegar.sender'),
                        'receptor' => $message->getReceiver()
                    ],
                    $message->getMessage()
                )
            )
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = resolve(Notification::class)->send($smsChannel, $message);

        $this->assertTrue($result);
    }
}