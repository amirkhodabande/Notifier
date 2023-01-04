<?php

namespace Amir\Notifier\Console;

use Amir\Notifier\Channels\CustomMailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Models\Notification;
use Illuminate\Console\Command;

class ClearFailedNotifications extends Command
{
    protected $signature = 'notification:clear-fails {channel?}';

    protected $description = 'Clear failed notifications.';

    public function handle()
    {
        $channel = $this->argument('channel');

        Notification::where('status', false)
            ->when($channel, function ($query) use ($channel) {
                $query->where('channel', $channel);
            })
            ->delete();

        $this->info('Operation finished successfully!');
    }
}