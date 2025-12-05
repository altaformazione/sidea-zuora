<?php

// config for Sideagroup/Zuora
return [
    'base_uri' => env('ZUORA_BASE_URI', 'https://rest.eu.zuora.com'),
    'credentials' => [
        'client_id' => env('ZUORA_CLIENT_ID'),
        'client_secret' => env('ZUORA_CLIENT_SECRET'),
        'entity_id' => env('ZUORA_ENTITY_ID'),
    ],
    'log_requests' => env('ZUORA_LOG_REQUESTS', false),
];
