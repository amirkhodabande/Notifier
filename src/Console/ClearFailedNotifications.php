<?php

namespace Amir\Notifier\Console;

use Amir\Notifier\Models\Notification;
use Illuminate\Console\Command;

class ClearFailedNotifications extends Command
{
    protected $signature = 'notification:clear-fails';

    protected $description = 'Clear failed notifications.';

    public function handle()
    {
        Notification::where('status', false)->delete();

        $this->info('Operation finished successfully!');
    }
}