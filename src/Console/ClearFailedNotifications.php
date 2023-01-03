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
        $channel = $this->getChannelClassByName($this->argument('channel'));

        if($this->argument('channel') && !$channel) {
            $this->info('The entered channel name is invalid!');
            return false;
        }

        Notification::where('status', false)
            ->when($channel, function ($query) use ($channel) {
                $query->where('channel', $channel);
            })
            ->delete();

        $this->info('Operation finished successfully!');
    }

    private function getChannelClassByName(?string $channelName): ?string
    {
        $channels = [
            'mail' => CustomMailChannel::class,
            'sms' => SMSChannel::class
        ];

        if(!$channelName) {
            return null;
        }

        return optional($channels)[$channelName];
    }
}