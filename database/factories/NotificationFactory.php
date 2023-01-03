<?php

use Amir\Notifier\Channels\MailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Models\Notification;

$factory->define(Notification::class, function (Faker\Generator $faker) {
    return [
        'channel' => $faker->randomElement([
            MailChannel::class, SMSChannel::class
        ]),
        'status' => $faker->boolean,
        'provider_url' => $faker->url,
        'receiver' => $faker->email,
        'message' => ['message' => $faker->sentence]
    ];
});

