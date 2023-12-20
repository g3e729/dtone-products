<?php

return [
    'pin_only' => env('PRODUCT_PIN_ONLY', 0),
    'credentials' => [
        'EUR' => [
            'DTONE_KEY' => env('DTONE_KEY_EUR'),
            'DTONE_SECRET' => env('DTONE_SECRET_EUR'),
        ],
        'GBP' => [
            'DTONE_KEY' => env('DTONE_KEY'),
            'DTONE_SECRET' => env('DTONE_SECRET'),
        ],
        'USD' => [
            'DTONE_KEY' => env('DTONE_KEY_USD'),
            'DTONE_SECRET' => env('DTONE_SECRET_USD'),
        ],
    ],
];
