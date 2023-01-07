<?php

namespace Amir\Notifier\Tests\Unit;

use Amir\Notifier\Models\Notification;
use Amir\Notifier\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class ListFailedNotificationsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_list_all_failed_notifications()
    {
        $notifications = factory(Notification::class)->times(5)->create(['status' => false]);

        $this->artisan('notification:list')
            ->expectsOutputToContain("Id: {$notifications->first()->id} - Receiver: {$notifications->first()->receiver} - Channel: {$notifications->first()->channel}")
            ->expectsOutputToContain("Operation finished successfully!");
    }

    /** @test */
    public function it_will_filter_list_by_date()
    {
        $notifications = factory(Notification::class)->times(2)->create([
            'status' => false,
            'created_at' => Carbon::now()->subDays(4)
        ]);
        $otherNotifications = factory(Notification::class)->times(2)->create([
            'status' => false,
            'created_at' => Carbon::now()
        ]);

        $this->artisan('notification:list', [
            'from' => Carbon::now()->subWeek()->toDateString(),
            'to' => Carbon::now()->subDays(3)->toDateString()
        ])
            ->expectsOutputToContain("Id: {$notifications->first()->id} - Receiver: {$notifications->first()->receiver} - Channel: {$notifications->first()->channel}")
            ->doesntExpectOutput("Id: {$otherNotifications->first()->id} - Receiver: {$otherNotifications->first()->receiver} - Channel: {$otherNotifications->first()->channel}")
            ->expectsOutputToContain("Operation finished successfully!");
    }
}