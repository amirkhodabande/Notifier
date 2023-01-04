<?php

return [
    'sms' => [
        'test-provider' => [
            'from' => '09101234567',
            'url' => 'https://www.sms.com',
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