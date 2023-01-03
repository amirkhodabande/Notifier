<?php


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
}