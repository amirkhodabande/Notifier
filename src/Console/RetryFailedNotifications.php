<?php

namespace Amir\Notifier\Console;

use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Messages\ValueObjects\MailMessage;
use Amir\Notifier\Messages\ValueObjects\SMSMessage;
use Amir\Notifier\Models\Notification;
use Amir\Notifier\Services\Notification as NotificationService;
use Illuminate\Console\Command;

class RetryFailedNotifications extends Command
{
    protected $signature = 'notification:retry-fails {channel?}';

    protected $description = 'Retry failed notifications.';

    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly NotifiableData $notifiableData,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $channel = $this->getChannelClassByName($this->argument('channel'));

        if ($this->argument('channel') && !$channel) {
            $this->info('The entered channel name is invalid!');
            return false;
        }

        $notifications = Notification::query()
            ->when($channel, function ($query) use ($channel) {
                $query->where('channel', $channel);
            })
            ->where('status', false);

        foreach ($notifications->cursor() as $notification) {
            $notifiableChannel = resolve($notification->channel)->setReceiver($notification->receiver);
            $notifiableData = $this->notifiableData->setMessage($this->getProperMessageForChannel($notification));

            $this->notificationService->send($notifiableChannel, $notifiableData);

            $this->info("Failed notification: {$notification->id} retried!");
        }

        $this->info('Operation finished successfully!');
    }

    private function getChannelClassByName(?string $channelName): ?string
    {
        $channels = [
            'mail' => MailChannel::class,
            'sms' => SMSChannel::class
        ];

        return optional($channels)[$channelName];
    }

    private function getProperMessageForChannel(Notification $notification)
    {
        switch ($notification->channel) {
            case MailChannel::class:
                return
                    new MailMessage($notification->message['subject'], $notification->message['message']);
                break;

            case SMSChannel::class:
                return
                    new SMSMessage($notification->message['message']);
                break;
        }
    }
}