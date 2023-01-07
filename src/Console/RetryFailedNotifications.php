<?php

namespace Amir\Notifier\Console;

use Amir\Notifier\Channels\CustomMailChannel;
use Amir\Notifier\Channels\KavenegarChannel;
use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Messages\NotifiableData;
use Amir\Notifier\Messages\ValueObjects\CustomMailMessage;
use Amir\Notifier\Messages\ValueObjects\MailMessage;
use Amir\Notifier\Messages\ValueObjects\SMSMessage;
use Amir\Notifier\Models\Notification;
use Amir\Notifier\Services\Notification as NotificationService;
use Illuminate\Console\Command;

class RetryFailedNotifications extends Command
{
    protected $signature = 'notification:retry-fails {id?} {channel?}';

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
        $id = $this->argument('id');
        $channel = $this->argument('channel');

        $notifications = Notification::query()
            ->when($id, function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($channel && !$id, function ($query) use ($channel) {
                $query->where('channel', $channel);
            })
            ->where('status', false);

        foreach ($notifications->cursor() as $notification) {
            $notifiableChannel = resolve($notification->channel);
            $notifiableData = $this->notifiableData->setReceiver($notification->receiver)
                ->setMessage($this->getProperMessageForChannel($notification));

            $this->notificationService->send($notifiableChannel, $notifiableData);

            $notification->update(['status' => true]);
            $this->info("Failed notification: {$notification->id} retried!");
        }

        $this->info('Operation finished successfully!');
    }

    private function getProperMessageForChannel(Notification $notification)
    {
        switch ($notification->channel) {
            case CustomMailChannel::class:
                return
                    new CustomMailMessage($notification->message['subject'], $notification->message['message']);
                break;

            case MailChannel::class:
                return
                    new MailMessage($notification->message['subject'], $notification->message['message'], $notification->message['view'] );
                break;

            case SMSChannel::class || KavenegarChannel::class:
                return
                    new SMSMessage($notification->message['message']);
                break;
        }
    }
}