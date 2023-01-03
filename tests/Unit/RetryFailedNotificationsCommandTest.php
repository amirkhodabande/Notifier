<?php


use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Models\Notification;
use Amir\Notifier\Services\Notification as NotificationService;
use Amir\Notifier\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class RetryFailedNotificationsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_retry_mail_failed_notifications()
    {
        $mockedNotificationService = \Mockery::mock(NotificationService::class);
        $mockedNotificationService->shouldReceive('send')
            ->once();
        $this->app->instance(NotificationService::class, $mockedNotificationService);

        factory(Notification::class)->create([
            'channel' => MailChannel::class,
            'status' => false,
            'message' => [
                'subject' => 'test subject', 'message' => 'test message'
            ]
        ]);

        Artisan::call('notification:retry-fails', ['channel' => 'mail']);
    }

    /** @test */
    public function it_will_retry_sms_failed_notifications()
    {
        $mockedNotificationService = \Mockery::mock(NotificationService::class);
        $mockedNotificationService->shouldReceive('send')
            ->once();
        $this->app->instance(NotificationService::class, $mockedNotificationService);

        factory(Notification::class)->create([
            'channel' => SMSChannel::class,
            'status' => false,
            'message' => [
                'message' => 'test message'
            ]
        ]);

        Artisan::call('notification:retry-fails', ['channel' => 'sms']);
    }

    /** @test */
    public function it_can_automatically_detect_channel()
    {
        $mockedNotificationService = \Mockery::mock(NotificationService::class);
        $mockedNotificationService->shouldReceive('send')
            ->times(4);
        $this->app->instance(NotificationService::class, $mockedNotificationService);

        factory(Notification::class)->times(2)->create([
            'channel' => SMSChannel::class,
            'status' => false,
            'message' => [
                'message' => 'test message'
            ]
        ]);

        factory(Notification::class)->times(2)->create([
            'channel' => SMSChannel::class,
            'status' => false,
            'message' => [
                'message' => 'test message'
            ]
        ]);

        Artisan::call('notification:retry-fails');
    }
}