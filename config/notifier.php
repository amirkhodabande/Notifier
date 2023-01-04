<?php

return [
    'sms' => [
        'test-provider' => [
            'from' => '09101234567',
            'url' => 'https://www.sms.com',
        ],
        'kavenegar' => [
            'url' => 'https://api.kavenegar.com/v1/',
            'api-key' => '',
            'sender' => '1000596446'
        ]
    ],
    'email' => [
        'custom-provider' => [
            'from' => 'sender@mail.com',
            'url' => 'https://www.mail.com',
        ],
        'laravel-mail' => [
//       it will use the mail driver of your project.
        ]
    ]
];