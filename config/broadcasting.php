<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | Laravel mendukung beberapa sistem broadcasting: "pusher", "ably",
    | "redis", "log", dan "null". Kita ubah ke "reverb" biar realtime jalan.
    |
    */

    'default' => env('BROADCAST_DRIVER', 'log'),


    /*
    |--------------------------------------------------------------------------
    | Connections
    |--------------------------------------------------------------------------
    |
    | Di sini kamu bisa mendefinisikan semua koneksi broadcasting
    | yang tersedia untuk aplikasi kamu.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'host' => env('PUSHER_HOST', '127.0.0.1'),
                'port' => (int) env('PUSHER_PORT', 6001),
                'scheme' => env('PUSHER_SCHEME', 'http'),
                'useTLS' => false,
                'encrypted' => false,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

        /*
        |--------------------------------------------------------------------------
        | 🎧 Reverb Connection (Realtime Laravel)
        |--------------------------------------------------------------------------
        */

        /*
        'reverb' => [
            'driver' => 'pusher', // Reverb memakai protocol Pusher
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'app_id' => env('REVERB_APP_ID'),
            'options' => [
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => (int) env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'useTLS' => false,
            ],
        ],
*/
    ],

];
