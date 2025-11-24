<?php
// DB config - edit for XAMPP
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'flyhighenglish',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
    'openai_api_key' => getenv('OPENAI_API_KEY') ?: null,
    'google' => [
        'client_id' => '384837898610-vvfcdmvre6hfq7o89fu9hojk40c7tqha.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect_uri' => 'http://localhost/flyhighenglish/public/google-callback.php'
    ]
];
