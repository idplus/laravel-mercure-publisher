<?php
return [
    'hub' => [
        'url' => env('MERCURE_PUBLISH_URL','http://127.0.0.1:3000/hub'),
        'jwt' => env('MERCURE_JWT_SECRET'),
    ],
    // 'jwt_provider' => "\App\CustomJwt",
    'jwt_provider' => null,
    'queue_name' => null,
];