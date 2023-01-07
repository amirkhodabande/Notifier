<?php

namespace Amir\Notifier\Console;

use Amir\Notifier\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ListFailedNotifications extends Command
{
    protected $signature = 'notification:list {from?} {to?}';

    protected $description = 'Show list of the failed notifications.';

    public function handle()
    {
        Notification::where('status', false)
            ->select('id', 'channel', 'receiver')
            ->when($this->argument('from'), function ($query) {
                $query->whereBetween('created_at', [
                    $this->argument('from'),
                    $this->argument('to') ?: Carbon::now()
                ]);
            })
            ->get()
            ->map(function ($notification) {
                $this->info("Id: {$notification->id} - Receiver: {$notification->receiver} - Channel: {$notification->channel}");
                $this->line('---');
            });

        $this->info('Operation finished successfully!');
    }
}