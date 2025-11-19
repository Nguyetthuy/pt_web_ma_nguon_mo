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
    'openai_api_key' => getenv('OPENAI_API_KEY') ?: null
];
