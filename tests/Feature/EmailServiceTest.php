<?php

use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Messages\NotifiableMessage;
use Amir\Notifier\Services\Notification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class EmailServiceTest extends TestCase
{
    /** @test */
    public function user_can_send_email()
    {
        $mailChannel = (new MailChannel());
        $message = (new NotifiableMessage())->setMessage([
            'subject' => 'test subject',
            'message' => 'test message'
        ]);

        Http::shouldReceive('retry')
            ->once()
            ->with($mailChannel->getRetryTime(), $mailChannel->getSleepTime())
            ->andReturnSelf();
        Http::shouldReceive('post')
            ->once()
            ->with($mailChannel->getUrl(), $message->getMessage())
            ->andReturn(response()->json(['message' => 'mail sent successfully'], Response::HTTP_OK));

        $result = (new Notification())->send($mailChannel, $message);

        $this->assertTrue($result);
    }
}