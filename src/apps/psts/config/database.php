<?php
/**
 * Added to override ALL the default databases setting in vendor section.
 * 
 */
return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'fetch' => PDO::FETCH_ASSOC, 
    'connections' => [

        'testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ],

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', base_path('database/database.sqlite')),
            'prefix'   => env('DB_PREFIX', ''),
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => env('DB_CHARSET', 'utf8'),
            'prefix'   => env('DB_PREFIX', ''),
            'schema'   => env('DB_SCHEMA', 'public'),
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => env('DB_CHARSET', 'utf8'),
            'prefix'   => env('DB_PREFIX', ''),
        ],

    ],
    'migrations' => 'migrations',
];
