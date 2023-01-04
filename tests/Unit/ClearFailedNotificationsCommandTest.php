<?php


use Amir\Notifier\Channels\CustomMailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Models\Notification;
use Amir\Notifier\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class ClearFailedNotificationsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_clear_all_failed_notifications()
    {
        factory(Notification::class)->times(5)->create(['status' => false]);

        Artisan::call('notification:clear-fails');

        $this->assertDatabaseEmpty('notifications');
    }

    /** @test */
    public function it_is_able_to_remove_only_mail_channel_failed_notifications()
    {
        factory(Notification::class)->times(2)->create([
            'channel' => SMSChannel::class
            ,'status' => false
        ]);
        factory(Notification::class)->times(5)->create([
            'channel' => CustomMailChannel::class
            ,'status' => false
        ]);

        Artisan::call('notification:clear-fails', ['channel' => CustomMailChannel::class]);

        $this->assertDatabaseCount('notifications', 2);
    }

    /** @test */
    public function it_is_able_to_remove_only_sms_channel_failed_notifications()
    {
        factory(Notification::class)->times(2)->create([
            'channel' => CustomMailChannel::class
            ,'status' => false
        ]);
        factory(Notification::class)->times(5)->create([
            'channel' => SMSChannel::class
            ,'status' => false
        ]);

        Artisan::call('notification:clear-fails', ['channel' => SMSChannel::class]);

        $this->assertDatabaseCount('notifications', 2);
    }

    /** @test */
    public function it_will_not_remove_notifications_when_an_invalid_channel_name_entered()
    {
        factory(Notification::class)->times(5)->create([
            'channel' => SMSChannel::class
            ,'status' => false
        ]);

        Artisan::call('notification:clear-fails', ['channel' => 'invalid_channel']);

        $this->assertDatabaseCount('notifications', 5);
    }
}