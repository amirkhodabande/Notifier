<?php

use Amir\Notifier\Channels\CustomMailChannel;
use Amir\Notifier\Channels\SMSChannel;
use Amir\Notifier\Models\Notification;

$factory->define(Notification::class, function (Faker\Generator $faker) {
    return [
        'channel' => $faker->randomElement([
            CustomMailChannel::class, SMSChannel::class
        ]),
        'status' => $faker->boolean,
        'receiver' => $faker->email,
        'message' => ['message' => $faker->sentence]
    ];
});

